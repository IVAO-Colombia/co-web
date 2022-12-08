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
        session(["url.intended" => url()->previous()]);
        if (
            session("url.intended") == config("app.url") . "/login" ||
            session("url.intended") == config("app.url") . "/auth/ivao/callback"
        ) {
            session(["url.intended" => "/"]);
        }

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

            //caso especial Julian
            if ($user["vid"] == "653841") {
                $user["staff"] = ["CO-WMA1"];
            }

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

            return redirect(session("url.intended"));
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function sso_application()
    {
        // Get all URLs we need from the server
        $openid_url = "https://api.ivao.aero/.well-known/openid-configuration";
        $openid_result = file_get_contents($openid_url, false);
        if ($openid_result === false) {
            /* Handle error */
            die("Error while getting openid data");
        }
        $openid_data = json_decode($openid_result, true);

        $token_req_data = [
            "grant_type" => "client_credentials",
            "client_id" => "57b2d957-38ff-4d1e-8d8f-7e5aa8d0d5fe",
            "client_secret" => "VUFqej5bLDOBngOtUcQCF97U1o7MQDbu",
            "scope" => "profile",
        ];

        // use key 'http' even if you send the request to https://...
        $token_options = [
            "http" => [
                "header" =>
                    "Content-type: application/x-www-form-urlencoded\r\n",
                "method" => "POST",
                "content" => http_build_query($token_req_data),
            ],
        ];
        $token_context = stream_context_create($token_options);
        $token_result = file_get_contents(
            $openid_data["token_endpoint"],
            false,
            $token_context
        );
        if ($token_result === false) {
            /* Handle error */
            die("Error while getting token");
        }
        $token_res_data = json_decode($token_result, true);
        $access_token = $token_res_data["access_token"]; // Here is the access token

        // Now we can use the access token to get the data

        $tracker_url = "https://api.ivao.aero/v2/tracker/now/pilots/summary";
        $tracker_options = [
            "http" => [
                "header" => "Authorization: Bearer $access_token\r\n",
                "method" => "GET",
            ],
        ];
        $tracker_context = stream_context_create($tracker_options);
        $tracker_result = file_get_contents(
            $tracker_url,
            false,
            $tracker_context
        );
        if ($tracker_result === false) {
            /* Handle error */
            die("Error while getting tracker data");
        }
        $tracker_res_data = json_decode($tracker_result, true);

        dd($tracker_res_data); // Display data fetched with the application token
    }

    public function sso()
    {
        // dd(session("ivao_tokens"));
        session(["url.intended" => url()->previous()]);
        if (
            session("url.intended") == config("app.url") . "/login" ||
            session("url.intended") == config("app.url") . "/auth/callback"
        ) {
            session(["url.intended" => "/"]);
        }

        // Now we can take care of the actual authentication
        $client_id = env("IVAO_CLIENTID");
        $client_secret = env("IVAO_SECRET");
        $redirect_uri = route("ivao.login-sso-callback");

        // Get all URLs we need from the server
        $openid_url = "https://api.ivao.aero/.well-known/openid-configuration";
        $openid_result = file_get_contents($openid_url, false);
        if ($openid_result === false) {
            /* Handle error */
            die("Error while getting openid data");
        }
        $openid_data = json_decode($openid_result, true);

        $base_url = $openid_data["authorization_endpoint"];
        $reponse_type = "code";
        $scopes = "profile configuration email";
        $state = "1234567890"; // Random string to prevent CSRF attacks

        $query = [
            "response_type" => $reponse_type,
            "client_id" => $client_id,
            "scope" => $scopes,
            "redirect_uri" => $redirect_uri,
            "state" => $state,
        ];
        $full_url = "$base_url?" . http_build_query($query);

        if (isset($_GET["code"]) && isset($_GET["state"])) {
            // User has been redirected back from the login page

            $code = $_GET["code"]; // Valid only 15 seconds

            $token_req_data = [
                "grant_type" => "authorization_code",
                "code" => $code,
                "client_id" => $client_id,
                "client_secret" => $client_secret,
                "redirect_uri" => $redirect_uri,
            ];

            // use key 'http' even if you send the request to https://...
            $token_options = [
                "http" => [
                    "header" =>
                        "Content-type: application/x-www-form-urlencoded\r\n",
                    "method" => "POST",
                    "content" => http_build_query($token_req_data),
                ],
            ];
            $token_context = stream_context_create($token_options);
            $token_result = file_get_contents(
                $openid_data["token_endpoint"],
                false,
                $token_context
            );
            if ($token_result === false) {
                /* Handle error */
                die("Error while getting token");
            }

            $token_res_data = json_decode($token_result, true);

            $access_token = $token_res_data["access_token"]; // Here is the access token
            $refresh_token = $token_res_data["refresh_token"]; // Here is the refresh token

            session([
                "ivao_tokens" => json_encode([
                    "access_token" => $access_token,
                    "refresh_token" => $refresh_token,
                ]),
            ]); // 30 days
            return redirect($full_url);
            // header("Location: user.php"); // Remove the code and state from URL since they aren't valid anymore
        } elseif (session()->has("ivao_tokens")) {
            // User has already logged in

            $tokens = json_decode(session("ivao_tokens"), true);
            $access_token = $tokens["access_token"];
            $refresh_token = $tokens["refresh_token"];

            // Now we can use the access token to get the data

            $user_options = [
                "http" => [
                    "header" => "Authorization: Bearer $access_token\r\n",
                    "method" => "GET",
                    "ignore_errors" => true,
                ],
            ];
            $user_context = stream_context_create($user_options);
            $user_result = file_get_contents(
                $openid_data["userinfo_endpoint"],
                false,
                $user_context
            );
            $user_res_data = json_decode($user_result, true);

            if (
                (isset($user_res_data["description"]) &&
                    $user_res_data["description"] ===
                        "This auth token has been revoked or expired") ||
                (isset($user_res_data["description"]) &&
                    $user_res_data["description"] ===
                        "Couldn't decode auth token")
            ) {
                // Access token expired, using refresh token to get a new one

                $token_req_data = [
                    "grant_type" => "refresh_token",
                    "refresh_token" => $refresh_token,
                    "client_id" => $client_id,
                    "client_secret" => $client_secret,
                ];

                $token_options = [
                    "http" => [
                        "header" =>
                            "Content-type: application/x-www-form-urlencoded\r\n",
                        "method" => "POST",
                        "content" => http_build_query($token_req_data),
                        "ignore_errors" => true,
                    ],
                ];
                $token_context = stream_context_create($token_options);
                $token_result = file_get_contents(
                    $openid_data["token_endpoint"],
                    false,
                    $token_context
                );
                if ($token_result === false) {
                    /* Handle error */
                    die("Error while refreshing token");
                }

                $token_res_data = json_decode($token_result, true);

                $access_token = $token_res_data["access_token"]; // Here is the new access token
                $refresh_token = $token_res_data["refresh_token"]; // Here is the new refresh token

                session([
                    "ivao_tokens" => json_encode([
                        "access_token" => $access_token,
                        "refresh_token" => $refresh_token,
                    ]),
                ]);

                return redirect($full_url);
                // header("Location: user.php"); // Try to use the access token again
            } else {
                // dd($user_res_data); // Display user data fetched with the access token
                return $this->handlerLogin($user_res_data);
            }
        } else {
            // First visit : Unauthenticated user
            return redirect($full_url);
        }
    }

    public function handlerLogin($user)
    {
        $finduser = User::where("id", intval($user["id"]))->first();

        //caso especial Julian
        if ($user["id"] == "653841") {
            $user["staff"] = ["CO-WMA1"];
        }

        if ($finduser) {
            $finduser->firstname = $user["firstName"];
            $finduser->lastname = $user["lastName"];
            $finduser->email = $user["email"];
            // $finduser->rating = implode($user["rating"]);
            $finduser->ratingatc = intval($user["rating"]["atcRating"]["id"]);
            $finduser->ratingpilot = intval(
                $user["rating"]["pilotRating"]["id"]
            );
            $finduser->division = $user["divisionId"];
            $finduser->country = $user["countryId"];
            $finduser->staff = staffLogin($user["userStaffPositions"]);
            // $finduser->va_staff_ids = implode(",", $user["va_staff_ids"]);
            // $finduser->va_member_ids = implode(",", $user["va_member_ids"]);
            $finduser->save();
            Auth::login($finduser);
        } else {
            $newUser = User::create([
                "id" => intval($user["id"]),
                "firstname" => $user["firstName"],
                "lastname" => $user["lastName"],
                "email" => $user["email"],
                // "rating" => intval($user["rating"]),
                "ratingatc" => intval($user["rating"]["atcRating"]["id"]),
                "ratingpilot" => intval($user["rating"]["pilotRating"]["id"]),
                "division" => $user["divisionId"],
                "country" => $user["countryId"],
                "staff" => staffLogin($user["userStaffPositions"]),
                // "va_staff_ids" => implode(",", $user["va_staff_ids"]),
                // "va_member_ids" => implode(",", $user["va_member_ids"]),
                "password" => bcrypt("colombia"),
            ]);

            Auth::login($newUser);
        }

        $userlog = Auth::user();
        syncTeams($userlog);

        return redirect(session("url.intended"));
    }
}
