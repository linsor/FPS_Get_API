<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\api\v1\GPURequest;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class GetFPSController extends GetDataController
{

    public function getFPS (GPURequest $request) 
    {
        $data = $request->validated();
        $data['GPU'] = str_replace(' ', '+', $data['GPU']);


        $endpoint = "?type=&sort=&or=0&search={$data['GPU']}&gameselect%5B%5D={$data['Game']}";
        $response = $this->import->client->request('GET', $endpoint);
        $crawler = new Crawler($response->getBody());

        $values = $crawler->filter('tr.odd')->each(function (Crawler $node, $i) use ($data) {
            $tdValues = $node->filter("td.bv_{$data['Game']}")->each(function (Crawler $tdNode, $j) use ($data) {
                $spanNode = $tdNode->filter("span[class^='bl_med_val_{$data['Game']}_']")->first();
                return $spanNode->count() > 0 ? $spanNode->text() : null;
            });
            return $tdValues;
        });
    
        return $values;
    
    }    
}



             