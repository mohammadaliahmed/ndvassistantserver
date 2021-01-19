<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Departments;
use App\Faq;
use App\PasswordResets;
use App\User;
use App\Role;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    //

    public function register(Request $request)
    {

        if ($request->api_username != Constants::$API_USERNAME && $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_FORBIDDEN);
        } else {
            $user = DB::table('users')
                ->where('email', $request->email)
                ->first();

            $user2 = DB::table('users')
                ->where('phone', $request->phone)
                ->first();
            $user3 = DB::table('users')
                ->where('username', $request->username)
                ->first();

            if ($user != null) {
                return response()->json([
                    'code' => 302, 'message' => 'Email already exist',
                ], Response::HTTP_OK);
            } else if ($user2 != null) {
                return response()->json([
                    'code' => 302, 'message' => 'Phone already exist',
                ], Response::HTTP_OK);
            } else if ($user3 != null) {
                return response()->json([
                    'code' => 302, 'message' => 'Username already exist',
                ], Response::HTTP_OK);
            } else {

                if ($request->name == null) {
                    return response()->json([
                        'code' => 302, 'message' => 'Empty params',
                    ], Response::HTTP_OK);
                } else {
                    $milliseconds = round(microtime(true) * 1000);
                    $my_rand_strng = Constants::generateRandomString(7);


                    $user = new User();
                    $user->name = $request->name;
                    $user->username = $request->username;
                    $user->phone = $request->phone;
                    $user->gender = $request->gender;
                    $user->housenumber = $request->housenumber;
                    $user->block = $request->block;
                    $user->email = $request->email;
                    $user->password = bcrypt($request->password);
                    $user->save();
                    $role = Role::where('name', 'client')->first();
                    $user->roles()->attach($role->id);
                    $user->save();
                    return response()->json([
                        'code' => Response::HTTP_OK, 'message' => "false", 'user' => $user
                        ,
                    ], Response::HTTP_OK);
                }

            }

        }
    }


    public function updateProfile(Request $request)
    {

        if ($request->api_username != Constants::$API_USERNAME || $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_OK);
        } else {
            $user = User::find($request->id);
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->gender = $request->gender;
            $user->housenumber = $request->housenumber;
            $user->block = $request->block;

            if ($request->has('liveUrl')) {
                $user->avatar = $request->liveUrl;
            }
            $user->update();
            return response()->json([
                'code' => Response::HTTP_OK, 'message' => "false", 'user' => $user
                ,
            ], Response::HTTP_OK);


        }
    }

    public function login(Request $request)
    {

        if ($request->api_username != Constants::$API_USERNAME || $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_OK);
        } else {

            if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password])) {
                $user = DB::table('users')->where('phone', $request->phone)->first();
                $role = DB::table('role_user')->where('user_id', $user->id)->get();
                $user->role = Role::find($role[0]->role_id)->name;
                return response()->json([
                    'code' => 200, 'message' => "false", 'user' => $user
                    ,
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'code' => 302, 'message' => 'Wrong credentials',
                ], Response::HTTP_OK);
            }
        }

    }


    public function resetpassword(Request $request)
    {

        if ($request->api_username != Constants::$API_USERNAME || $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_OK);
        } else {


            $user = User::where('phone', $request->phone)->first();
            if ($user == null) {
                return response()->json([
                    'code' => 302, 'message' => 'This phone does not exists',
                ], Response::HTTP_OK);
            } else {

                $user = User::find($user->id);

                $code = Constants::generateRandomNumber(6);
                $user->code = $code;
                $user->update();
                $url = "https://telenorcsms.com.pk:27677/corporate_sms2/api/auth.jsp?msisdn=923453480541&password=yahoo123456";
//                $response = file_get_contents($url);


                $ch = curl_init();
                $timeout = 120;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");


                $response = curl_exec($ch);
                // close handle to release resources
                curl_close($ch);

                $xml = simplexml_load_string($response);
                echo $response;
                echo $xml;
                return ;
//                $value = (string)$xml->data[0];
//
//                return $value;

                $messageToCustomer = "Your reset code is: " . $code;
                $messageToCustomer = str_replace(" ", "%20", $messageToCustomer);
                $newUrl = "https://telenorcsms.com.pk:27677/corporate_sms2/api/sendsms.jsp?session_id=" . $value . "&to=92" . substr($request->phone, 1, 11) . "&text=" . $messageToCustomer . "&mask=4B%20Group%20PK";

                $urll = file_get_contents($newUrl);

                return response()->json([
                    'code' => Response::HTTP_OK, 'message' => 'false', 'user' => $user
                ], Response::HTTP_OK);
            }
        }

    }

    public function resetpasswordNow(Request $request)
    {

        if ($request->api_username != Constants::$API_USERNAME || $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_OK);
        } else {


            $user = User::where('phone', $request->phone)->first();
            if ($user == null) {
                return response()->json([
                    'code' => 302, 'message' => 'This phone does not exists',
                ], Response::HTTP_OK);
            } else {

                $user = User::find($user->id);

                if ($user->code == $request->code) {
                    $user->password = bcrypt($request->password);
                    $user->update();
                } else {
                    return response()->json([
                        'code' => Response::HTTP_BAD_REQUEST, 'message' => 'Wrong code. Please check again'
                    ], Response::HTTP_OK);
                }

                return response()->json([
                    'code' => Response::HTTP_OK, 'message' => 'false', 'user' => $user
                ], Response::HTTP_OK);
            }
        }

    }

    public
    function updateFcmKey(Request $request)
    {

        if ($request->api_username != Constants::$API_USERNAME && $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_OK);
        } else {
            $user = User::find($request->id);
            $user->fcmKey = $request->fcmKey;
            $user->update();
            $role = DB::table('role_user')->where('user_id', $user->id)->get();
            $user->role = Role::find($role[0]->role_id)->name;
            return response()->json([
                'code' => Response::HTTP_OK, 'message' => "false", 'user' => $user
            ], Response::HTTP_OK);
        }
    }

    public
    function appFaqs(Request $request)
    {

        if ($request->api_username != Constants::$API_USERNAME && $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_OK);
        } else {
            $faqs = Faq::all();
            foreach ($faqs as $faq) {
                $faq->department_name = Departments::find($faq->department_id)->name;
            }

            return response()->json([
                'code' => Response::HTTP_OK, 'message' => "false", 'faqs' => $faqs
            ], Response::HTTP_OK);
        }
    }
}
