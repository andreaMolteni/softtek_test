<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * WeatherService - Verion 1.0 (PHP Version 7.4.9):
 * This class get an array containing the list of the cities where TUI Musement has activities to sell
 * @note in case of some error return an empty array
 * 
 * @author Andrea Molteni - molteni.engineer@gmail.com
 */
class WeatherService
{

    private ContainerBagInterface $params;
    public string $apiKey;
    public string $apiBaseUrl;

    public function __construct(ContainerBagInterface $params)
    {
        $this->params = $params;
        $this->apiKey = is_string($this->params->get('app.api_weather_key')) ? $this->params->get('app.api_weather_key') :  '';
        $this->apiBaseUrl = is_string($this->params->get('app.api_weather_url')) ? $this->params->get('app.api_weather_url') : '';
    }

    /**
     * @param float $lat - latitude
     * @param float $lon - longitude
     * @param int $days - number of forecast days
     * @return array<string> - response array on forcast taxt for each days
     */
    function getWeather(float $lat = null, float $lon = null, int $days = 2): array
    {
        if($lat === null || $lon === null){
            return $this->manageResponse(null, $days); //no coordinates -> forecast not available
        }

        // build request url
        $queryFields = [
            'q' => number_format($lat,3,'.','') . ',' . number_format($lon,3,'.',''),
            'key' => $this->apiKey,
            'days' => $days
        ];
        $urlRequest = $this->apiBaseUrl . '?' . http_build_query($queryFields);

        $client = HttpClient::create();
        $response = $client->request('GET', $urlRequest);

        return  $this->manageResponse($response, $days);
    }

    /**
     * @param ResponseInterface $response - endpoint response
     * @param int $days - forecast number days
     * @return array<string> - repsonse array with with the forecast fo each days
     */
    public function manageResponse(ResponseInterface $response = null, int $days = 0): array
    {
        $weather = array_fill(0, $days, 'not available') ;
        if(!empty($response) && $response->getStatusCode() === 200)
        {
            $content = $response->toArray();
            for ($i=0; $i < $days; $i++){ 
                $weather[$i] = self::getWeatherText($content, $i);
            }
        }
        return $weather;
    }

    /**
     * @param array<mixed> $content - api weather response payload
     * @param int $when 
     * @return string weather text description or string 'not availble' 
     */
    public function getWeatherText(array $content = [], int $when): string
    {
        $weather = 'not available';
        $forecastDay = !empty($content['forecast']['forecastday']) ? $content['forecast']['forecastday'] : [];
        if( is_array($forecastDay) && count($forecastDay) >= $when ){
            $weather = !empty($forecastDay[$when]['day']['condition']['text']) ? $forecastDay[$when]['day']['condition']['text']  : 'not available';
        } 
        return $weather;
    }
}

?>