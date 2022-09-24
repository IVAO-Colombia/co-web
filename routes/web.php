<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    FrontController,
    IvaoController,
    EventController,
    SliderController
};

use App\Http\Livewire\Admin\{Events, Sliders, Airports, Virtualairlines};

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

Route::get("locale/{locale}", function ($locale) {
    Session::put("locale", $locale);
    return redirect()->back();
});

Route::controller(FrontController::class)->group(function () {
    Route::get("/", "index")->name("Home");
    Route::get("/about", "about")->name("front.about");
    Route::get("/fra", "fra")->name("front.fra");
    Route::get("/event-detail/{event:slug}", "event_detail")->name(
        "front.event_detail"
    );

    Route::get("/contact/send", "sendcontact")->name("front.sendcontact");

    Route::get("/fasttrack/{callsign}", "fasttrack")->name("front.fasttrack");
});

/*
IVAO Login
*/

Route::get("auth/ivao", [IvaoController::class, "redirect"])->name(
    "ivao.login"
);

Route::get("auth/ivao/callback", [IvaoController::class, "callback"]);
Route::get("auth/ivao/logout", function () {
    Auth::logout();
    return redirect()->route("Home");
})->name("ivao.logout");

/**
 * Staff panel
 */
Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
])->group(function () {
    Route::get("/dashboard", function () {
        return view("dashboard");
    })->name("dashboard");

    Route::get("/staff/events", Events::class)->name("events.index");
    Route::get("/staff/sliders", Sliders::class)->name("sliders.index");
    Route::get("/staff/airports", Airports::class)->name("airports.index");
    Route::get("/staff/virtualairlines", Virtualairlines::class)->name(
        "virtualairlines.index"
    );
});

Route::get("login", function () {
    return redirect("/auth/ivao/callback");
});
