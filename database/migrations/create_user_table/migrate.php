<?php

use Illuminate\Database\Capsule\Manager;

Manager::schema()->create('users', function ($table) {
    $table->increments('id');
    $table->string('email')->unique();
    $table->timestamps();
});