# ORM Model in Laravel 9 - Complete Guide

## Definition

**ORM (Object-Relational Mapping)** is a programming technique that lets you interact with your database using objects in your code instead of writing raw SQL queries.

**Laravel Model** is a class that represents a database table and provides an interface to interact with that table. 
Laravel uses **Eloquent ORM** - a simple yet powerful ORM for database interactions.

---

## What is Eloquent ORM?

Eloquent is Laravel's built-in ORM that allows you to:

- Work with database records as objects
- Write database queries in PHP instead of SQL
- Establish relationships between models
- Perform CRUD operations easily
- Use powerful query builder methods

---

## Creating a Model

### Basic Model Creation

```bash
php artisan make:model ModelName
```

Creates a model in `app/Models/` directory.

### Create Model with Migration

```bash
php artisan make:model ModelName -m
```

Creates both model and migration file.

### Create Model with Migration, Factory, and Seeder

```bash
php artisan make:model ModelName -mfs
```

---

## Example Model Structure (Laravel 9)

### User Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;

    protected $table = 'users';  // Table name (optional if plural of model name)
    protected $primaryKey = 'id'; // Primary key (optional, default is 'id')
    protected $keyType = 'int';   // Primary key type
    public $timestamps = true;    // Enable created_at and updated_at
    protected $fillable = [       // Mass assignable attributes
        'name',
        'email',
        'password',
        'phone'
    ];
    protected $hidden = [         // Hidden from JSON responses
        'password',
        'remember_token'
    ];
    protected $casts = [          // Cast attributes to types
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean'
    ];
}
```

---

## CRUD Operations with ORM

### CREATE - Inserting Records

**Method 1: Using create()**
```php
// Using mass assignment with fillable
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => bcrypt('password123'),
    'phone' => '1234567890'
]);
```

**Method 2: Using make() and save()**
```php
$user = new User();
$user->name = 'Jane Doe';
$user->email = 'jane@example.com';
$user->password = bcrypt('password123');
$user->save();
```

**Method 3: Using save() with new instance**
```php
$user = User::make([
    'name' => 'Bob Smith',
    'email' => 'bob@example.com'
]);
$user->save();
```

**Method 4: Using firstOrCreate()**
```php
$user = User::firstOrCreate(
    ['email' => 'existing@example.com'],
    ['name' => 'New User', 'password' => bcrypt('pass')]
);
```

### READ - Retrieving Records

**Get all records**
```php
$users = User::all();
```

**Get by primary key**
```php
$user = User::find(1);
$user = User::findOrFail(1);  // Throws 404 if not found
```

**Get first record**
```php
$user = User::first();
$user = User::firstOrFail();
```

**Get with WHERE clause**
```php
$user = User::where('email', 'john@example.com')->first();
$users = User::where('is_active', 1)->get();
```

**Get with multiple conditions**
```php
$users = User::where('is_active', 1)
    ->where('role', 'admin')
    ->get();
```

**Get with OR condition**
```php
$users = User::where('name', 'John')
    ->orWhere('name', 'Jane')
    ->get();
```

**Count records**
```php
$count = User::count();
$count = User::where('is_active', 1)->count();
```

**Pagination**
```php
$users = User::paginate(15);  // 15 users per page
$users = User::where('status', 'active')->paginate(10);
```

### UPDATE - Updating Records

**Method 1: Using update()**
```php
$user = User::find(1);
$user->update([
    'name' => 'Updated Name',
    'email' => 'updated@example.com'
]);
```

**Method 2: Direct property assignment**
```php
$user = User::find(1);
$user->name = 'New Name';
$user->email = 'newemail@example.com';
$user->save();
```

**Method 3: Using updateOrCreate()**
```php
User::updateOrCreate(
    ['email' => 'john@example.com'],
    ['name' => 'John Updated', 'phone' => '9876543210']
);
```

**Method 4: Mass update using where()**
```php
User::where('is_active', 0)
    ->update(['status' => 'inactive']);
```

### DELETE - Deleting Records

**Delete single record**
```php
$user = User::find(1);
$user->delete();

// Or directly
User::destroy(1);
```

**Delete multiple records**
```php
User::destroy([1, 2, 3]);
```

**Delete with condition**
```php
User::where('is_active', 0)->delete();
```

---

## Model Relationships

### One-to-Many Relationship

**Define in User Model (Parent)**
```php
public function posts()
{
    return $this->hasMany(Post::class);
}
```

**Define in Post Model (Child)**
```php
public function user()
{
    return $this->belongsTo(User::class);
}
```

**Usage**
```php
// Get all posts by user
$posts = $user->posts;

// Get user of a post
$user = $post->user;

// Create related post
$user->posts()->create([
    'title' => 'New Post',
    'content' => 'Post content'
]);
```

### Many-to-Many Relationship

**Define in User Model**
```php
public function roles()
{
    return $this->belongsToMany(Role::class);
}
```

**Define in Role Model**
```php
public function users()
{
    return $this->belongsToMany(User::class);
}
```

**Usage**
```php
// Get all roles of user
$roles = $user->roles;

// Attach role to user
$user->roles()->attach(1);

// Detach role from user
$user->roles()->detach(1);

// Sync roles (replace existing)
$user->roles()->sync([1, 2, 3]);
```

### One-to-One Relationship

**Define in User Model**
```php
public function profile()
{
    return $this->hasOne(Profile::class);
}
```

**Define in Profile Model**
```php
public function user()
{
    return $this->belongsTo(User::class);
}
```

**Usage**
```php
// Get profile
$profile = $user->profile;

// Get user
$user = $profile->user;
```

---

## Query Methods

### Filtering

```php
// Where conditions
User::where('age', '>', 18)->get();
User::where('age', '>=', 18)->get();
User::where('status', 'active')->get();
User::whereIn('role', ['admin', 'editor'])->get();
User::whereNotIn('role', ['guest'])->get();
User::whereBetween('age', [18, 65])->get();
User::whereNull('deleted_at')->get();
User::whereNotNull('phone')->get();
```

### Ordering

```php
User::orderBy('name', 'asc')->get();
User::orderBy('created_at', 'desc')->get();
User::latest()->get();  // Latest first
User::oldest()->get();  // Oldest first
```

### Limiting

```php
User::limit(10)->get();
User::take(5)->get();
User::skip(10)->take(5)->get();  // Pagination style
```

### Distinct

```php
User::distinct()->get();
```

### Aggregation

```php
User::count();
User::max('age');
User::min('age');
User::avg('age');
User::sum('salary');
```

---

## Eager Loading (N+1 Problem Solution)

**Problem: N+1 Query**
```php
$users = User::all();
foreach ($users as $user) {
    echo $user->profile->bio;  // Executes query for each user
}
```

**Solution: Eager Loading with with()**
```php
$users = User::with('profile')->get();
foreach ($users as $user) {
    echo $user->profile->bio;  // No additional queries
}
```

**Multiple Relations**
```php
$users = User::with('profile', 'posts', 'roles')->get();
```

**Nested Relations**
```php
$users = User::with('posts.comments')->get();
```

---

## Model Observers

Track model events (creating, created, updating, updated, deleting, deleted, restoring, restored)

```php
<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function creating(User $user)
    {
        // Before creating
    }

    public function created(User $user)
    {
        // After created
    }

    public function updating(User $user)
    {
        // Before updating
    }

    public function updated(User $user)
    {
        // After updated
    }

    public function deleting(User $user)
    {
        // Before deleting
    }
}
```

**Register Observer in AppServiceProvider**
```php
public function boot()
{
    User::observe(UserObserver::class);
}
```

---

## Key Model Properties

| Property | Purpose |
|----------|---------|
| `$table` | Database table name |
| `$primaryKey` | Primary key column |
| `$keyType` | Primary key type (int, string) |
| `$timestamps` | Enable created_at/updated_at |
| `$fillable` | Mass assignable columns |
| `$guarded` | Protected from mass assignment |
| `$hidden` | Hidden in JSON responses |
| `$casts` | Type casting for attributes |
| `$appends` | Append custom attributes |

---

## Best Practices

1. **Always use fillable or guarded** - Prevent mass assignment vulnerabilities
2. **Use eager loading** - Avoid N+1 query problems
3. **Validate input** - Use Form Requests or validation rules
4. **Use relationships** - Instead of manual joins
5. **Cast attributes** - Use $casts for type safety
6. **Use factories** - For testing and seeding
7. **Use transactions** - For complex operations
8. **Index frequently queried columns** - In migrations
9. **Use soft deletes** - When appropriate
10. **Follow naming conventions** - Models singular, tables plural

---

## Complete Controller Example

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Create
    public function store(Request $request)
    {
        $user = User::create($request->validated());
        return redirect()->route('users.show', $user->id);
    }

    // Read
    public function index()
    {
        $users = User::paginate(15);
        return view('users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // Update
    public function update(Request $request, User $user)
    {
        $user->update($request->validated());
        return redirect()->route('users.show', $user->id);
    }

    // Delete
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }
}
```

---

## Conclusion

Eloquent ORM is a powerful and elegant way to interact with databases in Laravel 9. It abstracts SQL complexity, provides a fluent interface, and promotes clean, maintainable code. Understanding relationships, eager loading, and query optimization is essential for building efficient Laravel applications.
