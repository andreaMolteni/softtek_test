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
     * @return array<mixed> - response array with keys: 'today' and 'tomorrow'
     */
    function getWeather(float $lat = null, float $lon = null, int $days = 2): array
    {
        if($lat === null || $lon === null){
            return $this->manageResponse(); //no coordinates -> forecast not available
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

        return  $this->manageResponse($response);
    }

    /**
     * @param ResponseInterface $response - endpoint response
     * @return array{'today': string, 'tomorrow': string} - repsonse array with keys: 'today' and 'tomorrow'
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
                "today" => self::getWeatherText($content, 'today'),
                "tomorrow" => self::getWeatherText($content, 'tomorrow')
            ];
        }
        return $weather;
    }

    /**
     * @param array<mixed> $content - api weather response payload
     * @param 'today'|'tomorrow' $when 
     * @return string weather text description or string 'not availble' 
     */
    public function getWeatherText(array $content = [], string $when): string
    {
        $weather = 'not available';
        $forecastDay = !empty($content['forecast']['forecastday']) ? $content['forecast']['forecastday'] : [];
        if( is_array($forecastDay) && count($forecastDay) >= 2 ){
            $index = $when === 'today' ? 0 : 1;
            $weather = !empty($forecastDay[$index]['day']['condition']['text']) ? $forecastDay[$index]['day']['condition']['text']  : 'not available';
        } 
        return $weather;
    }
}

?>