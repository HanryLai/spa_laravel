<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return ['Laravel' => app()->version()];
    return "Hello world";
});

require __DIR__.'/auth.php';