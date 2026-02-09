<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculties', function (Blueprint $table) {
             $table->increments("id");
             $table->unsignedInteger("depid");
             $table->foreign("depid")->references("id")->on("departments")->onDelete('cascade');
             $table->unsignedInteger("studentid");
             $table->foreign("studentid")->references("id")->on("students")->onDelete('cascade');
             
              $table->string('name');
              $table->integer('salary');
              $table->bigInteger('phone');
              $table->text('address');
            
             $table->unsignedInteger("countryid");
             $table->foreign("countryid")->references("id")->on("countries")->onDelete('cascade');
             
             $table->unsignedInteger("stateid");
             $table->foreign("stateid")->references("id")->on("states")->onDelete('cascade');
             

             $table->unsignedInteger("cityid");
             $table->foreign("cityid")->references("id")->on("cities")->onDelete('cascade');
             
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faculties');
    }
};
