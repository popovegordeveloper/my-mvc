<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('migrations', function ($table) {
    $table->increments('id');
    $table->string('name');
});