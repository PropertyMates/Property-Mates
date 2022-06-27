<?php

use Twilio\Rest\Client;

class CoOwner_Twilio {

    public static function sand_message($to,$message){
        $to = strlen($to) > 10 ? $to : '61'.$to;
        try {
            $twilio_account_sid = get_option('_crb_twilio_account_sid');
            $twilio_auth_token = get_option('_crb_twilio_auth_token');
            $twilio_from_number = get_option('_crb_twilio_from_number');
            $client = new Client($twilio_account_sid, $twilio_auth_token);
            $response = $client->messages->create(
                $to,[
                    'from' => $twilio_from_number,
                    'body' => $message
                ]
            );
            if($response->status == 'queued'){
                return (object) array(
                    'status' => true,
                    'message' => 'The message has been sent'
                );
            } else {
                return (object) array(
                    'status' => false,
                    'message' => 'Something went wrong please try again.'
                );
            }
        } catch (\Exception $exception){
            $message = str_replace(['[HTTP 400] Unable to create record:'],"",$exception->getMessage());
            return (object) array(
                'status' => false,
                'message' => trim($message)
            );
        }
    }
}

