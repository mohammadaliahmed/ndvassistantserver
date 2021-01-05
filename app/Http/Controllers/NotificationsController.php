<?php

namespace App\Http\Controllers;

use App\SendNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class NotificationsController extends Controller
{
    //

    public function index()
    {
        return view('admin.notifications');
    }

    public function sendnotification(Request $request)
    {
        $users = User::all();
        foreach ($users as $user) {
            if ($user->hasRole('client') && $user->fcmKey != null) {
                $sendNotification = new SendNotification();
                $sendNotification->sendPushNotification($user->fcmKey,
                    $request->title,
                    $request->message,
                    1,
                    'notification'
                );
            }
        }
        return redirect::to('admin/notifications')->withMessage('Notification sent to customers');
    }
}
