<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class MusementService
{

    private $params;
    public $url;

    public function __construct(ContainerBagInterface $params)
    {
        $this->params = $params;
        $this->url = $this->params->get('app.api_musement_url');;
    }

    /**
     * It returns an array response from musement api containing status code, message and cities list 
     * @return array  
     */
    function getCities(): array
    {
        $client = HttpClient::create();
        $response = $client->request('GET', $this->url,[
            'headers' => [
                'Accept' => 'application/json',
                'Accept-Language' => 'en-US'
            ],
        ]);

        return $response instanceof ResponseInterface ? $this->manageResponse($response) : $this->manageResponse();
        
    }

    /**
     * It manages the end point response. If the code is 200 then it returns the cities list
     * @param ResponseInterface $response 
     * @return array arrayKeys: [statusCode, message, citiesLilst [name, lat, lon]]
     */
    private function manageResponse(ResponseInterface $response = null): array
    {
        switch(empty($response) || $response->getStatusCode()){
            case 200:
                $content = $response->toArray();
                $citiesList = array_map(function($element){
                    return [
                        "name" => !empty($element['name']) ? $element['name'] : '',
                        "lat" => !empty($element['latitude']) ? $element['latitude'] : null,
                        "lon" => !empty($element['longitude']) ? $element['longitude'] : null,
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