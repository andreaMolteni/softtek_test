<?php
namespace App\Tests\Service;

use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class WeatherServiceTest extends KernelTestCase
{
    /**
     * It tests WeatherService without input
     */
    public function testWeatherServiceNoInput(): void
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        // (3) run some service & test the result
        $WeatherService = $container->get(WeatherService::class);
        $weather = $WeatherService->getWeather();

        $this->assertTrue(is_array($weather), 'WeatherService: the response is an array');
        $this->assertTrue(count($weather) > 0, 'WeatherService: the response is an array with length > 0');
        $this->assertTrue(isset($weather['today']), 'WeatherService: there is the key "today" in the response');
        $this->assertTrue($weather['today'] === 'not available', 'WeatherService: the key "today" in = "not available"');
        $this->assertTrue(isset($weather['tomorrow']), 'WeatherService: there is the key "tomorrow" in the response');
        $this->assertTrue($weather['tomorrow'] === 'not available', 'WeatherService: the key "tomorrow" in = "not available"');
    }

    // /**
    //  * It tests MusemenService in case of the response in not 200
    //  */
    // public function testMusementService400(): void
    // {
    //     // (1) boot the Symfony kernel
    //     self::bootKernel();

    //     // (2) use static::getContainer() to access the service container
    //     $container = static::getContainer();
    //     // (3) run some service & test the result
    //     $MusementService = $container->get(MusementService::class);
    //     $MusementService->url = 'https://api.musement.com/api/v3/cities/gxfgjhxgfj';
    //     $cities = $MusementService->getCities();

    //     $this->assertTrue(is_array($cities), 'MusementService: the response is an array');
    //     $this->assertTrue(count($cities) > 0, 'MusementService: the response is an array with length > 0');
    //     $this->assertTrue(isset($cities['statusCode']), 'MusementService: there is the key "statusCode" in the response');
    //     $this->assertTrue(is_int($cities['statusCode']), 'MusementService: the "statusCode" is int');
    //     $this->assertTrue($cities['statusCode'] !== 200, 'MusementService: the "statusCode" is\'t 200');
    //     $this->assertTrue(isset($cities['message']), 'MusementService: there is the key "message" in the response');
    //     $this->assertTrue(is_string($cities['message']), 'MusementService: the "message" in string');
    //     $this->assertTrue($cities['message'] === 'No resource found','MusementService: the "message" is "No resource found"');
    //     $this->assertTrue(isset($cities['citiesList']), 'MusementService: there is the key "citiesList" in the response');
    //     $this->assertTrue(is_array($cities['citiesList']), 'MusementService: the "citiesList" in an array');
    //     $this->assertTrue(count($cities['citiesList']) === 0, 'MusementService: the "citiesList" length is 0');
    // }
}

?>