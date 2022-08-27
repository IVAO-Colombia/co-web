<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Socialite;
use Auth;
use Exception;

class IvaoController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('ivao')->redirect();
    }

    public function callback()
    {
        try {

            $user = Socialite::driver('ivao')->user()->getRaw();

            if (!$user) {
                return redirect()->away('/');
            }

            $finduser = User::where('id', intval($user["vid"]))->first();

            if ($finduser) {
                Auth::login($finduser);
                $userToUpdate = Auth::user();
                $userToUpdate->firstname = $user["firstname"];
                $userToUpdate->lastname = $user["lastname"];
                $userToUpdate->rating = intval($user["rating"]);
                $userToUpdate->ratingatc = intval($user["ratingatc"]);
                $userToUpdate->ratingpilot = intval($user["ratingpilot"]);
                $userToUpdate->division = $user["division"];
                $userToUpdate->country = $user["country"];
                $userToUpdate->staff = implode(",", $user["staff"]);
                $userToUpdate->va_staff_ids = implode(",", $user["va_staff_ids"]);
                $userToUpdate->va_member_ids = implode(",", $user["va_member_ids"]);
                $userToUpdate->save();

                return redirect('/');
            } else {
                $newUser = User::create([
                    'id' => intval($user["vid"]),
                    'firstname' => $user["firstname"],
                    'lastname' => $user["lastname"],
                    'email' => $user["vid"],
                    'rating' => intval($user["rating"]),
                    'ratingatc' => intval($user["ratingatc"]),
                    'ratingpilot' => intval($user["ratingpilot"]),
                    'division' => $user["division"],
                    'country' => $user["country"],
                    'staff' => implode(",", $user["staff"]),
                    'va_staff_ids' => implode(",", $user["va_staff_ids"]),
                    'va_member_ids' => implode(",", $user["va_member_ids"]),
                    'password' => encrypt('colombia')
                ]);

                Auth::login($newUser);

                return redirect('/');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
