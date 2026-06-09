<?php

return [

    'twilio' => [
        'sid'            => env('TWILIO_SID'),
        'token'          => env('TWILIO_AUTH_TOKEN'),
        'whatsapp_from'  => env('TWILIO_WHATSAPP_FROM'),
        'whatsapp_to'    => env('TWILIO_WHATSAPP_TO'),   // or pull from DB Setting
    ],

];