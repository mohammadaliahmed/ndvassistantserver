<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Departments;
use App\Files;
use App\Notifications\NewTicket;
use App\Replies;
use App\SendNotification;
use App\Settings;
use App\Tickets;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AppTicketsController extends Controller
{
    //
    public function assignedTickets(Request $request)
    {

        if ($request->api_username != Constants::$API_USERNAME && $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_FORBIDDEN);
        } else {
            $tickets = DB::table('tickets')->where('assigned_to', $request->id)->orderBy('id', 'desc')->get();
            foreach ($tickets as $ticket) {
                if ($ticket->assigned_to != null) {
                    $ticket->staff = User::find($ticket->assigned_to);
                    $ticket->user = User::find($ticket->user_id);
                }
            }
            return response()->json([
                'code' => Response::HTTP_OK, 'message' => "false", 'tickets' => $tickets
                ,
            ], Response::HTTP_OK);

        }
    }

    public function homeTickets(Request $request)
    {

        if ($request->api_username != Constants::$API_USERNAME && $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_FORBIDDEN);
        } else {
            $tickets = DB::table('tickets')->where('user_id', $request->id)->limit(3)->orderBy('id', 'desc')->get();
            foreach ($tickets as $ticket) {
                if ($ticket->assigned_to != null) {
                    $ticket->staff = User::find($ticket->assigned_to);
                }
            }
            return response()->json([
                'code' => Response::HTTP_OK, 'message' => "false", 'tickets' => $tickets
                ,
            ], Response::HTTP_OK);

        }
    }

    public function allTickets(Request $request)
    {

        if ($request->api_username != Constants::$API_USERNAME && $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_FORBIDDEN);
        } else {
            $tickets = DB::table('tickets')->where('user_id', $request->id)->orderBy('id', 'desc')->get();
            foreach ($tickets as $ticket) {
                if ($ticket->assigned_to != null) {
                    $ticket->staff = User::find($ticket->assigned_to);
                }
            }
            return response()->json([
                'code' => Response::HTTP_OK, 'message' => "false", 'tickets' => $tickets
                ,
            ], Response::HTTP_OK);

        }
    }

    public function getDepartments(Request $request)
    {

        if ($request->api_username != Constants::$API_USERNAME && $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_FORBIDDEN);
        } else {

            $departments = DB::table('departments')->get();
            return response()->json([
                'code' => Response::HTTP_OK, 'message' => "false", 'departments' => $departments
                ,
            ], Response::HTTP_OK);

        }
    }

    public function getReplies(Request $request)
    {
        if ($request->api_username != Constants::$API_USERNAME && $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_FORBIDDEN);
        } else {
            $ticket = Tickets::find($request->ticketId);
            $replies = Replies::where('ticket_id', $request->ticketId)->get();
            foreach ($replies as $reply) {
                $file = Files::where('reply_id', $reply->id)->first();
                $user = User::find($reply->user_id);
                $reply->user = $user;
                if ($file != null) {
                    $reply->attachment = $file->name;
                }
            }
            return response()->json([
                'code' => Response::HTTP_OK, 'message' => "false", 'replies' => $replies, 'ticket' => $ticket
                ,
            ], Response::HTTP_OK);
        }
    }

    public function sendReply(Request $request)
    {
        if ($request->api_username != Constants::$API_USERNAME && $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_FORBIDDEN);
        } else {

            $reply = new Replies();
            $reply->reply = $request->reply;
            $reply->ticket_id = $request->ticketId;
            $reply->user_id = $request->userId;
            $reply->reply = $request->reply;
            $reply->save();

            if ($request->has('liveUrl')) {


                $files = new Files();
                $files->name = $request->liveUrl;
                $files->user_id = $request->userId;
                $files->ticket_id = $request->ticketId;
                $files->reply_id = $reply->id;
                $files->save();
            }

            $replies = DB::table('replies')->where('ticket_id', $request->ticketId)->get();
            foreach ($replies as $reply) {
                $file = Files::where('reply_id', $reply->id)->first();
                $user = User::find($reply->user_id);
                $reply->user = $user;
                if ($file != null) {
                    $reply->attachment = $file->name;
                }
            }
            return response()->json([
                'code' => Response::HTTP_OK, 'message' => "false", 'replies' => $replies
                ,
            ], Response::HTTP_OK);
        }
    }

    public function notices(Request $request)
    {

        if ($request->api_username != Constants::$API_USERNAME && $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_FORBIDDEN);
        } else {

            $notices = DB::table('notice_boards')->get();
            return response()->json([
                'code' => Response::HTTP_OK, 'message' => "false", 'notices' => $notices
                ,
            ], Response::HTTP_OK);

        }
    }

    public function createTicket(Request $request)
    {

        if ($request->api_username != Constants::$API_USERNAME && $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_FORBIDDEN);
        } else {
            $ticket = new Tickets();
            $ticket->department_id = $request->department_id;
            $ticket->user_id = $request->id;
            $ticket->token_no = rand(1000, 10000);
            $ticket->subject = $request->title;
            $ticket->description = $request->description;
            $ticket->priority = $request->priority;
            $ticket->status = 'open';
            $ticket->save();
            $title = $request->title;
            $ticket_id = $ticket->id;
            $user_name = User::find($request->id)->name;
            if ($request->has('liveUrl')) {
                $files = new Files();
                $files->name = $request->liveUrl;
                $files->user_id = $request->id;
                $files->ticket_id = $ticket->id;
                $files->reply_id = 0;
                $files->save();
            }
            $users = User::all();
            foreach ($users as $user) {
                if ($user->hasRole('admin')) {
                    $user->notify(new NewTicket($title, $user_name, $ticket_id));
                }
            }
            $settings = Settings::all()->first();
            $usr = User::find($request->id);
            $department = Departments::find($request->department_id);


            Mail::send('mails.appthanks', ['ticket' => $request, 'department' => $department, 'username' => $usr->name, 'subject' => $request->title], function ($message) use ($settings) {
                $message->from('support@ndvhs.com', 'NDVHS Sahoolat');
                $message->subject('New Ticket Created');
                $message->to($settings->admin_email);
            });

            return response()->json([
                'code' => Response::HTTP_OK, 'message' => "false", 'ticket' => $ticket
                ,
            ], Response::HTTP_OK);

        }
    }


    public function sendMail()
    {
        $ticket = Tickets::find(10);
        $department = Departments::find(10);
        $settings = Settings::all()->first();

        Mail::send('mails.testmail', ['ticket' => $ticket, 'department' => $department], function ($message) use ($settings) {
            $message->from('support@ndvhs.com', 'NDVHS Sahoolat');
            $message->subject('New Ticket Created');
            $message->to('m.aliahmed0@gmail.com');
        });
    }


    public function smsApp()
    {
        $response = file_get_contents('https://telenorcsms.com.pk:27677/corporate_sms2/api/auth.jsp?msisdn=923453480541&password=yahoo123456');
//        $response = str_replace('Auth_request', '', $response);
//        $sessionId = str_replace('OK', '', $response);

        $xml = simplexml_load_string($response);
        $value = (string)$xml->data[0];


        print_r($value);


//        $response = file_get_contents('https://telenorcsms.com.pk:27677/corporate_sms2/api/auth.jsp?msisdn=923453480541&password=yahoo123456');
//
//
//        $xml = simplexml_load_string($response);
//        $sessionId = (string)$xml->data[0];
//
//
//        $user = User::find(6);
//        $ticket = Tickets::find(45);
////
//        $messageToAdmin = "New ticket has been created by " . $user->name . '\nAddress: H#' . $user->housenumber .
//            ', Block' . $user->block . "\nToken: " . $ticket->token_no . '\nDate: ' . $ticket->created_at;;
//        $messageToAdmin = str_replace(" ", "%20", $messageToAdmin);
//        $messageToACustomer = "Your ticket has been submitted" . ". Token " . $ticket->token_no. 'Date: ' . $ticket->created_at;;
//        $messageToACustomer = str_replace(" ", "%20", $messageToACustomer);
////        return $messageToACustomer;
//        $phone = $user->phone;
//
//        $newUrl = "https://telenorcsms.com.pk:27677/corporate_sms2/api/sendsms.jsp?session_id=" . $value . "&to=92" . $phone . "&text=" . $messageToAdmin . "&mask=4B%20Group%20PK";
////        return $newUrl;

//        $urll = file_get_contents($newUrl);
//
//
//        return $newUrl;//         return json_encode($urll);


    }

    public function sendNotification()
    {
        $noti = new SendNotification();
        $noti->sendPushNotification("c8e5dQqsSya9Vvvsnl8-tw:APA91bE4bUrkk0egzoEOVo-_NGr4tTJNgpDPikB0dMnLiy6GUNQodUNOl5Zcvm_guMUWGvfQRc3-iZS0f09i4lx95d4pYZwp36jrLe5Fa6ygY-jLicb15eZmArnd5vmMvMrEOOW4tSbU",
            "Hey", "message", 1, "reply");
    }
}
