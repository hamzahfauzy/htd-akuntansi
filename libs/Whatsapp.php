<?php

class Whatsapp
{
    
    static function setMessage($to, $message)
    {
        if($to[0] == "0")
            $to = "62".substr($to,1);

        $conn = conn();
        $db   = new Database($conn);

        $auth = auth('api')->user ? auth('api') : auth(); 

        $message = $db->insert('messages', [
            'user_id' => $auth->user->id,
            'send_by' => 'Whatsapp',
            'target' => $to,
            'content' => $message
        ]);

        return $message;
    }

    static function send($to, $message)
    {
        $data = [
            'api_key' => config('WA_API_KEY'),
            'sender'  => config('WA_API_SENDER'),
            'number'  => $to,
            'message' => $message
        ];
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => config('WA_API_SEND_URL'),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 20,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode($data))
        );
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return $response;
    }
}