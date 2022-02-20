<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;


class WeatherService
{

    private $params;

    public function __construct(ContainerBagInterface $params)
    {
        $this->params = $params;
    }

    function getWeather($lat, $lon, $days){
        // define url
        $apiKey = $this->params->get('app.api_weather_key');
        $apiBaseUrl = $this->params->get('app.api_weather_url');

        $queryFields = [
            'q' => number_format($lat,3,'.','') . ',' . number_format($lon,3,'.',''),
            'key' => $apiKey, // define api key
            'days' => $days
        ];
        $urlRequest = $apiBaseUrl . '?' . http_build_query($queryFields);

        $client = HttpClient::create();
        $response = $client->request('GET', $urlRequest);

        return self::manageResponse($response);
    }

    /**
     * 
     */
    private function manageResponse(object $response = null): array
    {
        if($response->getStatusCode() === 200){
                $content = $response->toArray();

                $weather = [
                    "today" => self::getWeatherText($content, 'today'),
                    "tomorrow" => self::getWeatherText($content, 'tomorrow')
                ];
            
        } else {
            $weather = [
                "today" => 'not available',
                "tomorrow" => 'not available',
            ];
        }
         return $weather;
    }

    /**
     * @param array $content - api weather response payload
     * @param string @when - {'today'|'tomorrow'} 
     */
    private function getWeatherText(array $content = [], string $when): string
    {
        $weather = 'not available';
        if(count($content['forecast']['forecastday']) === 2){
            if($when === 'today'){
                $weather = !empty($content['forecast']['forecastday'][0]['day']['condition']['text']) ? $content['forecast']['forecastday'][0]['day']['condition']['text']  : 'not available';
            } else {
                $weather = !empty($content['forecast']['forecastday'][1]['day']['condition']['text']) ? $content['forecast']['forecastday'][1]['day']['condition']['text']  : 'not available';
            }
            return $weather;
        } 
        
    }
}

?>