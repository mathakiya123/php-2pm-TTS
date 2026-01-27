<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Contact;
class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('task.contact');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //create a validations
         $validated = $request->validate([
              
          'name' => 'required|max:255',
          'email' => 'required|email|max:255',
          'phone' => 'required|min:10|max:10',
          'subject' => 'required|max:255',
          'message' => 'required',
         ]);   

        //  create a ORM Elequent query builder for insert data
            
        $data=array(
            "name"=>$request->name,
            "email"=>$request->email,
            "phone"=>$request->phone,
            "subject"=>$request->subject,
            "message"=>$request->message
        );
        // insert elequent ORM model
        Contact::create($data);
        return redirect('/contact-us')->with('success','Thanks for contact with us');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
