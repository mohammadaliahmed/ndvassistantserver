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

            $user = User::where('email', $request->phone)->first();
            if ($user == null) {
                return response()->json([
                    'code' => 302, 'message' => 'This email does not exists',
                ], Response::HTTP_OK);
            } else {

//                $token=Constants::generateRandomString(50);
//                $passwordRest=PasswordResets::where('email',$request->email)->first();
//                if($passwordRest==null){
//                    $passwordRest = new PasswordResets();
//                    $passwordRest->email = $request->email;
//                    $passwordRest->token = $token;
//                    $passwordRest->save();
//                }else{
//                    DB::table('password_resets')
//                        ->where('email', $request->email)
//                        ->update(['token' => $token]);
//
//                }


//                $link='http://sahoolat.ndvhs.com/resetpassword/'.$token;

//                Mail::send('mails.apppasswordreset', ['link'=>$link], function ($message) use ($request) {
//                    $message->from('support@ndvhs.com', 'NDVHS Sahoolat');
//                    $message->subject('Password reset');
//                    $message->to($request->email);
//                });

                return response()->json([
                    'code' => 302, 'message' => 'false', 'user' => $user
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
    }  public
    function appFaqs(Request $request)
    {

        if ($request->api_username != Constants::$API_USERNAME && $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_OK);
        } else {
           $faqs=Faq::all();
           foreach($faqs as $faq){
               $faq->department_name=Departments::find($faq->department_id)->name;
           }

            return response()->json([
                'code' => Response::HTTP_OK, 'message' => "false", 'faqs' => $faqs
            ], Response::HTTP_OK);
        }
    }
}
