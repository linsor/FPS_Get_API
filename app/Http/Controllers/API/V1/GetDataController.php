<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Components\GetFPSDataClient;
use App\Http\Controllers\Controller;
use Symfony\Component\DomCrawler\Crawler;

class GetDataController extends Controller
{

    public $import;
    public $data = ['version' => '1.0'];


    public function getAlldata()
    {
        $endpoint = "?type=&sort=&or=0&search=&gameselect%5B%5D=&gpu_fullname=1";
        $response = $this->import->client->request('GET', $endpoint);
        $crawler = new Crawler($response->getBody());
        

        $this->data['game'] = $crawler->filter('select[name="gameselect[]"] option')->each(function (Crawler $node) 
        {
            $text = $node->text();
            $value = $node->attr('value');
            return ['text' => $text, 'value' => $value];
        });


        $this->data['GPU'] = $crawler->filter('td.specs.fullname')->each(function (Crawler $node) {
            return ['value' => $node->text()]; 
        });

        return $this->data;
    }

    public function __construct()
    {
        
        $this->import = new GetFPSDataClient;
    }
}


