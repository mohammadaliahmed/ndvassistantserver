<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SendNotification extends Model
{
    public function sendPushNotification($fcm_token, $title, $message, $id, $type)
    {
        $push_notification_key = 'AAAA0YUiLng:APA91bFaftYj6m-p6NpIkXpS2xLSFbnnBhYz-J1AHjA1f6gRkK3cpFH5SxPHgnUUJ11kNo9jTyDxmSKpPRTWwOmi3SBXVwaztf08iRQuQV6u51NuqasZ6XnOa4bivL4Ens-6z_OgJN5b';
        $url = "https://fcm.googleapis.com/fcm/send";
        $header = array("authorization: key=" . $push_notification_key . "",
            "content-type: application/json"
        );

        $postdata = '{
            "to" : "' . $fcm_token . '",
            "priority" : "high",
                
            "data" : {
                "Message" : "' . $message . '",
                "Type":"' . $type . '",
                "Title":"' . $title . '",
                "Username":"Salman",
                "Id" : "' . $id . '"              }
        }';

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }

}
