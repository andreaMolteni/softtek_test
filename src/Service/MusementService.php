<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;


/**
 * MusementService - Verion 1.0 (PHP Version 7.4.9):
 * This class get the forecast for the next 2 days from the geographic coordinates
 * @note
 * 
 * @author Andrea Molteni - molteni.engineer@gmail.com
 */
class MusementService
{

    private ContainerBagInterface $params;
    public string $url;

    /**
     * @param ContainerBagInterface $params - parameters setted in service.yaml 
     */
    public function __construct(ContainerBagInterface $params)
    {
        $this->params = $params;
        $this->url = is_string($this->params->get('app.api_musement_url')) ? $this->params->get('app.api_musement_url') : '';
    }

    /**
     * It returns an array response from musement api containing status code, message and cities list 
     * @return array<mixed> response array must contain keys: 'statusCode', 'message' and 'citiesList'
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
        
        return $this->manageResponse($response);    
    }

    /**
     * It manages the end point response. If the code is 200 then it returns the cities list
     * @param ResponseInterface $response
     * @return array<mixed> - arrayKeys: [statusCode, message, citiesLilst []|[name, lat, lon]]
     */
    private function manageResponse(ResponseInterface $response ): array
    {
        switch($response->getStatusCode()){
            case 200:
                $content = $response->toArray();
                $citiesList = array_map(
                    function(array $element): array
                    {
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
            case 404:
                return [
                    "statusCode" => 404,
                    "message" => 'No resource found',
                    "citiesList" => []
                ];
            case 503:
                return [
                    "statusCode" => 503,
                    "message" => 'Service unavailable',
                    "citiesList" => []
                ];
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