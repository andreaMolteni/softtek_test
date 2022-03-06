<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * WeatherService - Verion 1.0 (PHP Version 7.4.9):
 * This class get an array containing the list of the cities where TUI Musement has activities to sell.
 *
 * @note in case of some error return an empty array
 *
 * @author Andrea Molteni - molteni.engineer@gmail.com
 */
class WeatherService
{
    private ContainerBagInterface $params;
    private HttpClientInterface $client;
    private string $apiKey;
    private string $apiBaseUrl;

    public function __construct(ContainerBagInterface $params, HttpClientInterface $client)
    {
        $this->params = $params;
        $this->client = $client;
        if ($this->params->has('app.api_weather_url') && is_string($this->params->get('app.api_weather_url'))) {
            $this->apiBaseUrl = $this->params->get('app.api_weather_url');
        }
        if ($this->params->has('app.api_weather_key') && is_string($this->params->get('app.api_weather_key'))) {
            $this->apiKey = $this->params->get('app.api_weather_key');
        }
    }

    /**
     * @param float $lat  - latitude
     * @param float $lon  - longitude
     * @param int   $days - number of forecast days
     *
     * @return array<string> - response array on forcast taxt for each days
     */
    public function getWeather(float $lat = null, float $lon = null, int $days = 2): array
    {
        try {
            if ($lat === null || $lon === null) {
                return $this->manageResponse(null, $days); //no coordinates -> forecast not available
            }

            // build request url
            $queryFields = [
                'q' => number_format($lat, 3, '.', '').','.number_format($lon, 3, '.', ''),
                'key' => $this->apiKey,
                'days' => $days,
            ];
            $urlRequest = $this->apiBaseUrl.'?'.http_build_query($queryFields);

            $response = $this->client->request('GET', $urlRequest, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Accept-Language' => 'en-US',
                ],
            ]);

            return $this->manageResponse($response, $days);
        } catch (\Exception $th) {
            return [];
        }
    }

    /**
     * @param ResponseInterface $response - endpoint response
     * @param int               $days     - forecast number days
     *
     * @return array<string> - repsonse array with with the forecast fo each days
     */
    private function manageResponse(ResponseInterface $response = null, int $days = 0): array
    {
        $weather = [];
        if (!empty($response) && $response->getStatusCode() === 200) {
            $content = $response->toArray();
            for ($i = 0; $i < $days; ++$i) {
                $weather[$i] = $this->getWeatherText($content, $i);
            }
        } else {
            for ($i = 0; $i < $days; ++$i) {
                $weather[$i] = 'not available';
            }
        }

        return $weather;
    }

    /**
     * @param array<mixed> $content - api weather response payload
     *
     * @return string weather text description or string 'not available'
     */
    private function getWeatherText(array $content = [], int $when): string
    {
        $weather = 'not available';
        if (!empty($content['forecast'])) {
            $forecast = $content['forecast'];
        } else {
            $forecast = [];
        }
        if (is_array($forecast) &&
            array_key_exists('forecastday', $forecast) &&
            is_array($forecast['forecastday']) &&
            count($forecast['forecastday']) >= $when) {
            if (!empty($forecast['forecastday'][$when]['day']['condition']['text'])) {
                $weather = $forecast['forecastday'][$when]['day']['condition']['text'];
            }
        }

        return $weather;
    }
}
