<?php

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

Manager::schema()->table('users', function (Blueprint $table) {
    $table->dropColumn('username');
    $table->dropColumn('password');
    $table->dropColumn('admin');
});