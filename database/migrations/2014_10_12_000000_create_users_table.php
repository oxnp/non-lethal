<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->addColumn('tinyInteger', 'block', ['length' => 4])->default(0);
            $table->addColumn('tinyInteger', 'sendEmail', ['length' => 4])->default(0);
            $table->dateTime('registerDate')->nullable();
            $table->dateTime('lastvisitDate')->nullable();
            $table->string('activation',100)->nullable();
            $table->mediumText('params')->nullable();
            $table->dateTime('lastResetTime')->nullable();
            $table->integer('resetCount')->default(0);
            $table->string('otpKey',1000)->nullable();
            $table->string('otep',1000)->nullable();
            $table->addColumn('tinyInteger', 'requireReset', ['length' => 4])->default(0);
            $table->integer('role_id')->default(0);
            $table->rememberToken()->nullable();
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
        Schema::dropIfExists('users');
    }
}
