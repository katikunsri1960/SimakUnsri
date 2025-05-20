<?php

namespace App\Services\Feeder;

use GuzzleHttp\Client;

class FeederAct {
    // url Feeder Dikti
    private $url;
    // Username Feeder Dikti
    private $username;
    // Password
    private $password;
    //data
    private $act, $key;


    function __construct($act, $key) {

        $this->url = env('FEEDER_URL');
        $this->username = env('FEEDER_USERNAME');
        $this->password = env('FEEDER_PASSWORD');
        $this->act = $act;
        $this->key = $key;

    }

     public function runWS()
    {
        $token = $this->get_token();
        $params = [
            "token" => $token,
            "act"   => $this->act,
            "key" => $this->key
        ];

        $result = $this->service_native($params, $this->url);

        return $result;
    }

    // public function runWS()
    // {
    //     // dd($this->url);
    //     $client = new Client();
    //     $params = [
    //         "act" => "GetToken",
    //         "username" => $this->username,
    //         "password" => $this->password,
    //     ];

    //     $req = $client->post( $this->url, [
    //         'headers' => [
    //             'Content-Type' => 'application/json',
    //             'Accept' => 'application/json',
    //         ],
    //         'body' => json_encode($params)
    //     ]);

    //     $response = $req->getBody();
    //     $result = json_decode($response,true);

    //     if($result['error_code'] == 0) {
    //         $token = $result['data']['token'];
    //         $params = [
    //             "token" => $token,
    //             "act"   => $this->act,
    //             "key" => json_encode($this->key)
    //         ];

    //         $req = $client->post( $this->url, [
    //             'headers' => [
    //                 'Content-Type' => 'application/json',
    //                 'Accept' => 'application/json',
    //             ],
    //             'body' => json_encode($params)
    //         ]);

    //         $response = $req->getBody();

    //         $result = json_decode($response,true);

    //     }

    //     return $result;
    // }

    private function get_token()
    {
        $client = new Client();
        $params = [
            "act" => "GetToken",
            "username" => $this->username,
            "password" => $this->password,
        ];

        $req = $client->post( $this->url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode($params)
        ]);

        $response = $req->getBody();
        $result = json_decode($response,true);

        return $result['data']['token'];
    }

    private function service_native($data,$url,$type='POST') {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        // $headers[] = 'Authorization: Bearer '.$this->get_token();
        $headers[] = 'Content-Type: application/json';

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        $data = json_encode($data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //curl_setopt($ch, CURLOPT_HEADER, 1);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);

        //print_r($data);
        curl_close($ch);

        return json_decode($result, true);
    }
}
