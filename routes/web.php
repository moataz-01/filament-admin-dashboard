<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/', function (Request $request) {
    $request->validate([
        'name' => 'required'
    ]);
});
