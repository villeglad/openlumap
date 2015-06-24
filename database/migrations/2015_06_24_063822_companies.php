<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Companies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('vatcode');
            $table->string('address');
            $table->string('postcode');
            $table->string('city');
            $table->string('country');
            $table->string('www');
            $table->string('email');
            $table->string('phone');
            $table->string('lat');
            $table->string('lng');
            $table->string('industry');
            $table->dateTime('information_last_updated');
            $table->dateTime('date_founded');
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
        Schema::drop('companies');
    }
}
