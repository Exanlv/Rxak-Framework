<?php

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

Manager::schema()->create('users', function (Blueprint $table) {
    $table->increments('id');
    $table->string('email')->unique();
    $table->timestamps();
});