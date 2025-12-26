<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Geozone listing route
Route::get('/geozones', function (App\Models\Geozone $geozone) {
    return view('geozones.geozone-list', compact('geozone'));
})->name('geozones.index');

// Geozone creation route
Route::get('/create-geozone', function () {
    return view('geozones.create-geozone');
})->name('geozones.create');

// Geozone editing route
Route::get('geozones/{geozone}/edit', function (App\Models\Geozone $geozone) {
    return view('geozones.edit-geozone', compact('geozone'));
})->name('geozones.show');