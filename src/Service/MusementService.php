<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * MusementService - Verion 1.0 (PHP Version 7.4.9):
 * This class get the forecast for the next 2 days from the geographic coordinates.
 *
 * @note
 *
 * @author Andrea Molteni - molteni.engineer@gmail.com
 *
 * @property ContainerBagInterface $params
 * @property HttpClientInterface   $client
 * @property string                $url
 *
 * @method arary getCities()
 * @method array manageResponse()
 */
class MusementService
{
    private ContainerBagInterface $params;
    private HttpClientInterface $client;
    private string $url = '';

    /**
     * @param ContainerBagInterface $params - parameters setted in service.yaml
     */
    public function __construct(ContainerBagInterface $params, HttpClientInterface $client)
    {
        $this->params = $params;
        $this->client = $client;
        if ($this->params->has('app.api_musement_url') && is_string($this->params->get('app.api_musement_url'))) {
            $this->url = $this->params->get('app.api_musement_url');
        }
    }

    /**
     * It returns an array response from musement api containing status code, message and cities list.
     *
     * @return array<mixed> response array must contain keys: 'statusCode', 'message' and 'citiesList'
     */
    public function getCities(): array
    {
        try {
            $response = $this->client->request('GET', $this->url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Accept-Language' => 'en-US',
                ],
            ]);

            return $this->manageResponse($response);
        } catch (\Exception $e) {
            return [
                'statusCode' => 1001,
                'message' => 'Service unavailable',
                'citiesList' => [],
            ];
        }
    }

    /**
     * It manages the end point response. If the code is 200 then it returns the cities list.
     *
     * @return array<mixed> - response array with statusCode, message, and cities list with name, lat e lon
     */
    private function manageResponse(ResponseInterface $response): array
    {
        $responseCode = $response->getStatusCode();
        if ($responseCode === 200) {
            $content = $response->toArray();
            $citiesList = array_map(
                function (array $element): array {
                    return [
                        'name' => $element['name'] ?? '',
                        'lat' => !empty($element['latitude']) ? $element['latitude'] : null,
                        'lon' => !empty($element['longitude']) ? $element['longitude'] : null,
                    ];
                },
                $content
            );

            return [
                'statusCode' => 200,
                'message' => 'Success',
                'citiesList' => $citiesList,
            ];
        } elseif ($responseCode === 404) {
            return [
                'statusCode' => $responseCode,
                'message' => 'No resource found',
                'citiesList' => [],
            ];
        } elseif ($responseCode === 503) {
            return [
                'statusCode' => $responseCode,
                'message' => 'Service unavailable',
                'citiesList' => [],
            ];
        } else {
            return [
                'statusCode' => 500,
                'message' => 'Internal Server Error',
                'citiesList' => [],
            ];
        }
    }
}
