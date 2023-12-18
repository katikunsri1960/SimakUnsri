<?php

namespace App\Services\Feeder;

use GuzzleHttp\Client;

class FeederAPI {
    // url Feeder Dikti
    private $url;
    // Username Feeder Dikti
    private $username;
    // Password
    private $password;
    //data
    private $act, $offset, $limit, $order, $filter;


    function __construct($act, $offset, $limit, $order, ) {

        $this->url = env('FEEDER_URL');
        $this->username = env('FEEDER_USERNAME');
        $this->password = env('FEEDER_PASSWORD');
        $this->act = $act;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->order = $order;

    }

    public function runWS()
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

        if($result['error_code'] == 0) {
            $token = $result['data']['token'];
            $params = [
                "token" => $token,
                "act"   => $this->act,
                "offset" => $this->offset,
                "order" => $this->order,
                "limit" => $this->limit,
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

        }

        return $result;
    }
}
