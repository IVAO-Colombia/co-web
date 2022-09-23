<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{User, Team};
use Socialite;
use Auth;
use Exception;

class IvaoController extends Controller
{
    public function redirect()
    {
        return Socialite::driver("ivao")->redirect();
    }

    public function callback()
    {
        try {
            if (config("app.env") == "local") {
                $user = getUserLocal();
            } else {
                $user = Socialite::driver("ivao")
                    ->user()
                    ->getRaw();
            }

            if (!$user) {
                return redirect()->away("/");
            }

            $finduser = User::where("id", intval($user["vid"]))->first();

            if ($finduser) {
                $finduser->firstname = $user["firstname"];
                $finduser->lastname = $user["lastname"];
                $finduser->rating = intval($user["rating"]);
                $finduser->ratingatc = intval($user["ratingatc"]);
                $finduser->ratingpilot = intval($user["ratingpilot"]);
                $finduser->division = $user["division"];
                $finduser->country = $user["country"];
                $finduser->staff = implode(",", $user["staff"]);
                $finduser->va_staff_ids = implode(",", $user["va_staff_ids"]);
                $finduser->va_member_ids = implode(",", $user["va_member_ids"]);
                $finduser->save();
                Auth::login($finduser);
            } else {
                $newUser = User::create([
                    "id" => intval($user["vid"]),
                    "firstname" => $user["firstname"],
                    "lastname" => $user["lastname"],
                    "email" => $user["vid"],
                    "rating" => intval($user["rating"]),
                    "ratingatc" => intval($user["ratingatc"]),
                    "ratingpilot" => intval($user["ratingpilot"]),
                    "division" => $user["division"],
                    "country" => $user["country"],
                    "staff" => implode(",", $user["staff"]),
                    "va_staff_ids" => implode(",", $user["va_staff_ids"]),
                    "va_member_ids" => implode(",", $user["va_member_ids"]),
                    "password" => bcrypt("colombia"),
                ]);

                Auth::login($newUser);
            }

            $userlog = Auth::user();
            syncTeams($userlog);

            return redirect("/");
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
