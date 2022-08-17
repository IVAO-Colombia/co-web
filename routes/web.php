<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
Language
*/

Route::get('locale/{locale}', function ($locale) {
    Session::put('locale', $locale);
    return redirect()->back();
});



Route::controller(FrontController::class)->group(function () {
    Route::get('/', 'index');

});
