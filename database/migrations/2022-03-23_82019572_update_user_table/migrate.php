<?php

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

Manager::schema()->table('users', function (Blueprint $table) {
    $table->string('username');
    $table->string('password');
    $table->tinyInteger('admin');
});