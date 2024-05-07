<?php
namespace App\sms;

use Twilio\Rest\Client;

class SmsSender
{
    
    
          public function SendSms(string $number, string $name, string $text)
    {
        
        $accountSid ='AC0c7a0c7ff7476a5e4f2b23aa272decb2';  //Identifiant du compte twilio
        $authToken ='cf5bf79d1fba743e530331d18377da8b'; //Token d'authentification
        $fromNumber ='+12562634911'; // Numéro de test d'envoie sms offert par twilio

        $toNumber = $number; // Le numéro de la personne qui reçoit le message
        $message = ''.$name.' vous a envoyé le message suivant:'.' '.$text.''; //Contruction du sms

        //Client Twilio pour la création et l'envoie du sms
        $client = new Client($accountSid, $authToken);

        $client->messages->create(
            $toNumber,
            [
                'from' => $fromNumber,
                'body' => $message,
            ]
        );


    }




}