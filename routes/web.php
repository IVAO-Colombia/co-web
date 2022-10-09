<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    FrontController,
    IvaoController,
    EventController,
    SliderController
};

use App\Http\Livewire\Website\UpdateUser;

use App\Http\Livewire\Admin\{
    Events,
    Sliders,
    Airports,
    Virtualairlines,
    Trainings
};

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
    if (time() > strtotime("October 09 2022 23:59")) {
        Route::get("/", "index")->name("Home");
    } else {
        Route::get("/", "comingsoon")->name("comingsoon");
    }

    Route::get("/gca", "gca")->name("front.gca");
    Route::get("/about", "about")->name("front.about");
    Route::get("/fra", "fra")->name("front.fra");
    Route::get("/eventscalendar", "eventscalendar")->name(
        "front.eventscalendar"
    );
    Route::get("/virtualairlines", "virtualairlines")->name(
        "front.virtualairlines"
    );
    Route::get("/events", "events")->name("front.events");
    Route::get("/event-detail/{event:slug}", "event_detail")->name(
        "front.event_detail"
    );

    Route::get("/contact/send", "sendcontact")->name("front.sendcontact");
    Route::get("/updateuser", UpdateUser::class)
        ->name("front.updateuser")
        ->middleware(["auth"]);
    Route::get("/training", "training")
        ->name("front.training")
        ->middleware(["auth"]);
    Route::put("/user-update", "usersUpdate")
        ->name("front.usersUpdate")
        ->middleware(["auth"]);
    Route::post("/trainingatc", "trainingatc")
        ->name("front.trainingatc")
        ->middleware(["auth"]);
    Route::post("/trainingpilot", "trainingpilot")
        ->name("front.trainingpilot")
        ->middleware(["auth"]);

    Route::get("/fasttrack/{callsign}", "fasttrack")->name("front.fasttrack");
});

/*
IVAO Login
*/

Route::get("auth/ivao", [IvaoController::class, "redirect"])->name(
    "ivao.login"
);

Route::get("auth/ivao/callback", [IvaoController::class, "callback"])->name(
    "ivao.callback"
);
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
    Route::get("/staff/trainings", Trainings::class)->name("trainings.index");
    Route::get("/staff/virtualairlines", Virtualairlines::class)->name(
        "virtualairlines.index"
    );
});

Route::get("login", function () {
    return redirect("/auth/ivao");
});
