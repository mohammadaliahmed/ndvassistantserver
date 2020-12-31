<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Files;
use App\Notifications\NewTicket;
use App\Tickets;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AppTicketsController extends Controller
{
    //
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
            $ticket->status = 'open';
            $ticket->save();


            $title = $request->title;
            $ticket_id = $ticket->id;
            $user_name = User::find($request->id)->name;

//
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


            return response()->json([
                'code' => Response::HTTP_OK, 'message' => "false"
                ,
            ], Response::HTTP_OK);

        }
    }

}
