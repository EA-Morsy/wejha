<?php

namespace App\Utilities;

class WhatsappHelper
{
    public static function sendFile($number="+201007574516", $fileName, $fileUrl, $caption='')
    {
        $token  = env('CHAT_API_TOKEN');
        $apiUrl = env('CHAT_API_URL');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiUrl.'/sendFile?token='.$token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode(array("phone"=> static::scanNumber($number), "body" => $fileUrl, 'filename' => $fileName, 'caption'=> $caption)),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$token,
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public static function scanNumber($number)
    {
        if (!strpos($number, "+2") || !strpos($number, "002")) {
            $number = '+2'.$number;
        }
        return $number;
    }
}
