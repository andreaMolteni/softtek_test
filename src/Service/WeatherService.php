<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;


class WeatherService
{

    static function getWeather($lat, $lon, $days){
        // define url
        $apiKey = '097c03a6f4de4a0780a102038222002';
        $apiBaseUrl = 'http://api.weatherapi.com/v1/forecast.json';

        $queryFields = [
            'q' => number_format($lat,3,'.','') . ',' . number_format($lon,3,'.',''),
            'key' => $apiKey, // define api key
            'days' => $days
        ];
        $urlRequest = $apiBaseUrl . '?' . http_build_query($queryFields);

        $client = HttpClient::create();
        $response = $client->request('GET', $urlRequest);

        // return self::manageResponse($response);
        $content = $response->toArray();

        $response = [
            "today" => $content['forecast']['forecastday'][0]['day']['condition']['text'],
            "tomorrow" => $content['forecast']['forecastday'][1]['day']['condition']['text'],
        ];

        return $response;
    }

    // /**
    //  * 
    //  */
    // private function manageResponse(object $response = null): array
    // {
    //     if($response->getStatusCode() === 200){
    //             $content = $response->toArray();

    //             $weather = [
    //                 "today" => self::getWeather($content, 'today'),
    //                 "tomorrow" => self::getWeather($content, 'tomorrow')
    //             ];
            
    //     } else {
    //         $weather = [
    //             "today" => 'not available',
    //             "tomorrow" => 'not available',
    //         ];
    //     }
    //      return $weather;
    // }

    // /**
    //  * 
    //  */
    // private function getWeather(array $content = [], string $when): string
    // {
    //     $weather = 'not available';
    //     if($when === 'today'){
    //             $weather = !empty($content['forecast']['forecastday'][0]['day']['condition']['text']) ? $content['forecast']['forecastday'][0]['day']['condition']['text']  : 'not available',
    //     } else {
    //         $weather = !empty($content['forecast']['forecastday'][1]['day']['condition']['text']) ? $content['forecast']['forecastday'][1]['day']['condition']['text']  : 'not available',
    //     }
    //      return $weather;
    // }
}

?>