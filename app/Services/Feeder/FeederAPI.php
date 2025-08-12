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


    function __construct($act, $offset, $limit, $order, $filter = null) {

        $this->url = env('FEEDER_URL');
        $this->username = env('FEEDER_USERNAME');
        $this->password = env('FEEDER_PASSWORD');
        $this->act = $act;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->order = $order;
        $this->filter = $filter;

    }

    public function runWS()
    {
        // dd($this->url);
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
                "limit" => $this->limit,
                "filter" => $this->filter,
            ];

            // only add 'order' if act is NOT one of the excluded types
            $excludedActs = ['GetListRiwayatPendidikanMahasiswa'];
            if (!in_array($this->act, $excludedActs)) {
                $params['order'] = $this->order;
            }

            // dd(json_encode($params, JSON_UNESCAPED_SLASHES));
            $req = $client->post( $this->url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode($params, JSON_UNESCAPED_SLASHES)
            ]);

            $response = $req->getBody();

            $result = json_decode($response,true);

        }

        return $result;
    }
}
