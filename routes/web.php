<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//routes

// Home Page
Route::get('/', function () {
    return view('welcome');
})->name('index');

// About Page
Route::get('/about', function () {
    return view('about');
})->name('about');

// Achievements Page
Route::get('/achievements', function () {
    return view('achievements');
})->name('achievements');

// Team Page
Route::get('/team', function () {
    return view('team');
})->name('team');

// Testimonial Page
Route::get('/testimonial', function () {
    return view('testimonial');
})->name('testimonial');

// 404 Page
Route::get('/404', function () {
    return view('404');
})->name('404');

// Contact Page
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/joinusnow', function () {
    return view('joinusnow');
})->name('joinusnow');


Route::fallback(function () {
    return view('404');
});
