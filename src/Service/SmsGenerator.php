<?php
namespace App\Service;

use Twilio\Rest\Client;

class SmsGenerator
{
    
    
          public function SendSms(string $number, string $name, string $text)
    {
        
        $accountSid ='AC1bb2cf6c9b6a682decd00cf48f18a496';  //Identifiant du compte twilio
        $authToken ='d39271fbbb955444fcabdb4e12ff4757'; //Token d'authentification
        $fromNumber ='+12513158005'; // Numéro de test d'envoie sms offert par twilio

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