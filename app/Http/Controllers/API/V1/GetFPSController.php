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

        $fps = $crawler->filter('tr.odd')->each(function (Crawler $node) use ($data) {
            $tdValues = $node->filter("td.bv_{$data['Game']}")->each(function (Crawler $tdNode) use ($data) {
                $spanNode = $tdNode->filter("span[class^='bl_med_val_{$data['Game']}_']")->first();
                return ['value' => $spanNode->count() > 0 ? $spanNode->text() : null];
            });
            return $tdValues;
        });

        $fps = array_merge(...$fps);

        $settingPreset = $crawler->filter("td.gg_head_set_class.bh_{$data['Game']}")->each(function (Crawler $node, $i) {
            return ['value' => $node->text()]; 
        });


        $resolutions = [];
        $i = 0;
        foreach ($settingPreset as &$item)
        {
            $value = $this->formateValue($item['value']);

            if (preg_match('/((\d+x\d+\w*\s+Quality\s(.+))|(\d+x\d+\w*\s+\w*\s+Quality\s(.+))|(\d+x\d+\w*\s+\w*)|(\d+x\d+\w*))/', $value, $matches)) {
                $resolution = rtrim($matches[0]);
                
                $resolutions[str_replace(' ', '_', $resolution)] = $fps[$i]['value'];
                $i++;
            };
                
            
        }

        return $resolutions;
    }



     private function formateValue ($string){

        if (preg_match('/(\d+x\d+\w*\s\w*)\s+Preset\s\+\s(.+)/', $string, $matches)) {

            return preg_replace('/(\d+x\d+\w*\s\w*)\s+Preset\s\+\s(.+)/', '$1 $2', $string);
            
        } elseif (preg_match('/(\d+x\d+\w*)\s+Preset\s\+\s(.+)\s\+\s\w*|(\d+x\d+\w*)\s+Preset\s\+\s(.+)/', $string, $matches)){

            return preg_replace('/(\d+x\d+\w*)\s+Preset\s\+\s(.+)\s\+\s\w*|(\d+x\d+\w*)\s+Preset\s\+\s(.+)/', '$1 $2 $3 $4', $string);

        } else {

            return $string;
        }
        
    }

}
