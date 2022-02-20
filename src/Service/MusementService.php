<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class MusementService
{

    /**
     * It returns an array response from musement api containing status code, message and cities list 
     * @return array  
     */
    static function getCities(): array
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.musement.com/api/v3/cities/',[
            'headers' => [
                'Accept' => 'application/json',
                'Accept-Language' => 'en-US'
            ],
        ]);

        return self::manageResponse($response);
        
    }

    /**
     * It manages the end point response. If the code is 200 then it returns the cities list
     * @param object $response 
     * @return array arrayKeys: [statusCode, message, citiesLilst [name, lat, lon]]
     */
    private function manageResponse(object $response = null): array
    {
        switch($response->getStatusCode()){
            case 200:
                $content = $response->toArray();
                $citiesList = array_map(function($element){
                    return [
                        "name" => !empty($element['name']) ? $element['name'] : '',
                        "lat" => !empty($element['latitude']) ? $element['latitude'] : -1,
                        "lon" => !empty($element['longitude']) ? $element['longitude'] : -1,
                    ];
                }, $content);
                return [
                    "statusCode" => 200,
                    "message" => 'Returned when successful',
                    "citiesList" => $citiesList
                ];
                break;
            case 404:
                return [
                    "statusCode" => 404,
                    "message" => 'No resource found',
                    "citiesList" => []
                ];
                break;
            case 503:
                return [
                    "statusCode" => 503,
                    "message" => 'Service unavailable',
                    "citiesList" => []
                ];
                break;
            default:
                return [
                    "statusCode" => 400,
                    "message" => 'Server Error',
                    "citiesList" => []
                ];
        }
    }
}

?>