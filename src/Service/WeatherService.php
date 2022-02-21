<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class WeatherService
{

    private $params;
    public $apiKey;
    public $apiBaseUrl;

    public function __construct(ContainerBagInterface $params)
    {
        $this->params = $params;
        $this->apiKey = $this->params->get('app.api_weather_key');
        $this->apiBaseUrl = $this->params->get('app.api_weather_url');
    }

    function getWeather(float $lat = null, float $lon = null, int $days = 2){
        if($lat === null || $lon === null){
            $this->manageResponse();
        }

        $queryFields = [
            'q' => number_format($lat,3,'.','') . ',' . number_format($lon,3,'.',''),
            'key' => $this->apiKey,
            'days' => $days
        ];
        $urlRequest = $this->apiBaseUrl . '?' . http_build_query($queryFields);

        $client = HttpClient::create();
        $response = $client->request('GET', $urlRequest);

        return  $response instanceof ResponseInterface ? $this->manageResponse($response) : $this->manageResponse();
    }

    /**
     * 
     */
    public function manageResponse(ResponseInterface $response = null): array
    {
        if(empty($response) || $response->getStatusCode() !== 200){
            $weather = [
                "today" => 'not available',
                "tomorrow" => 'not available',
            ];
        } else {
            $content = $response->toArray();
            $weather = [
                "today" => is_array($content) ? self::getWeatherText($content, 'today') : 'not available',
                "tomorrow" => is_array($content) ? self::getWeatherText($content, 'tomorrow') : 'not available'
            ];
        }
        return $weather;
    }

    /**
     * @param array $content - api weather response payload
     * @param string @when - {'today'|'tomorrow'} 
     */
    public function getWeatherText(array $content = [], string $when): string
    {
        $weather = 'not available';
        $forecastDay = !empty($content['forecast']['forecastday']) ? $content['forecast']['forecastday'] : [];
        if( count($forecastDay) >= 2 ){
            if($when === 'today'){
                $weather = !empty($forecastDay[0]['day']['condition']['text']) ? $forecastDay[0]['day']['condition']['text']  : 'not available';
            } else {
                $weather = !empty($forecastDay[1]['day']['condition']['text']) ? $forecastDay[1]['day']['condition']['text']  : 'not available';
            }
        } 
        return $weather;
    }
}

?>