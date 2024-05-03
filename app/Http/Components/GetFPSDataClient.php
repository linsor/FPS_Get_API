<?php 

namespace App\Http\Components;
use GuzzleHttp\Client;
use Illuminate\Support\Env;

class GetFPSDataClient
{
    public $client;


    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('BASE_URL'),
            'timeout' => 2.0,
            'verify' => false,
        ]);
    }
}

 