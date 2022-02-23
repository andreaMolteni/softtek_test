<?php
namespace App\Tests;

use App\Service\WeatherService;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * MusementServiceTest - Verion 1.0 (PHP Version 7.4.9):
 * This class allows you to test for the WeatherService class methods
 * 
 * @author Andrea Molteni - molteni.engineer@gmail.com
 */
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
        // $this->assertTrue(count($weather) > 0, 'WeatherService: the response is an array with length > 0');
        // $this->assertTrue(isset($weather['today']), 'WeatherService: there is the key "today" in the response');
        // $this->assertTrue($weather['today'] === 'not available', 'WeatherService: the key "today" in = "not available"');
        // $this->assertTrue(isset($weather['tomorrow']), 'WeatherService: there is the key "tomorrow" in the response');
        // $this->assertTrue($weather['tomorrow'] === 'not available', 'WeatherService: the key "tomorrow" in = "not available"');
    }

    /**
     * It tests WeatherService with null coordinates
     */
    public function testWeatherServiceInputNull(): void
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        // (3) run some service & test the result
        $WeatherService = $container->get(WeatherService::class);
        $days = 2;
        $weather = $WeatherService->getWeather(null, null, $days);

        $this->assertTrue(is_array($weather), 'WeatherService: the response is an array');
        $this->assertTrue(count($weather) >= $days, 'WeatherService: the response is an array with length > ' . $days);
        for ($i=0; $i < $days; $i++) { 
            $this->assertTrue(isset($weather[$i]), 'WeatherService: there is the key ' . $days . 'in the response');
            $this->assertTrue($weather[$i] === 'not available', 'WeatherService: in the response key ' . $days . ' the value is = "not available"');
        }
    }

    /**
     * It tests WeatherService with bad coordinates
     */
    public function testWeatherServiceBadInput(): void
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        // (3) run some service & test the result
        $WeatherService = $container->get(WeatherService::class);
        
        $days = 2;
        $weather = $WeatherService->getWeather((float)'bfsdfhsf', (float)'gasksjdhf', $days);

        $this->assertTrue(is_array($weather), 'WeatherService: the response is an array');
        $this->assertTrue(count($weather) >= $days, 'WeatherService: the response is an array with length > ' . $days);
        for ($i=0; $i < $days; $i++) { 
            $this->assertTrue(isset($weather[$i]), 'WeatherService: there is the key ' . $days . 'in the response');
            $this->assertTrue($weather[$i] === 'not available', 'WeatherService: in the response key ' . $days . ' the value is = "not available"');
        }
    }
    
    /**
     * It tests WeatherService in normal conditions
     */
    public function testWeatherServiceNormal(): void
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        // (3) run some service & test the result
        $WeatherService = $container->get(WeatherService::class);
        $days = 2;
        $weather = $WeatherService->getWeather(48.138, 32.00, $days);

        $this->assertTrue(is_array($weather), 'WeatherService: the response is an array');
        $this->assertTrue(count($weather) >= $days, 'WeatherService: the response is an array with length > ' . $days);
        for ($i=0; $i < $days; $i++) { 
            $this->assertTrue(isset($weather[$i]), 'WeatherService: there is the key ' . $days . 'in the response');
            $this->assertTrue($weather[$i] !== 'not available', 'WeatherService: in the response key ' . $days . ' the value is = "not available"');
            $this->assertTrue($weather[$i] !== '', 'WeatherService:  in the response key ' . $days . ' the value is not empty');
        }
    }

    /**
     * It tests WeatherService with bad url
     */
    public function testWeatherServiceBadUrl(): void
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        // (3) run some service & test the result
        $WeatherService = $container->get(WeatherService::class);
        $WeatherService->apiBaseUrl = 'http://api.weatherapi.com/v1/gshadfhforecast.json/fhasdòkbljn';
        $days = 2;
        $weather = $WeatherService->getWeather(48.138, 32.00, $days);

        $this->assertTrue(is_array($weather), 'WeatherService: the response is an array');
        $this->assertTrue(count($weather) === $days, 'WeatherService: the response is an array with length = ' . $days);
        for ($i=0; $i < $days; $i++) { 
            $this->assertTrue(isset($weather[$i]), 'WeatherService: there is the key ' . $days . 'in the response');
            $this->assertTrue($weather[$i] === 'not available', 'WeatherService: in the response key ' . $days . ' the value is = "not available"');
        }
    }

    /**
     * It tests WeatherService with bad key
     */
    public function testWeatherServiceBadKey(): void
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        // (3) run some service & test the result
        $WeatherService = $container->get(WeatherService::class);
        $WeatherService->apiKey = '097c03a6f4de5643i6uh02038222002';
        $days = 2;
        $weather = $WeatherService->getWeather(48.138, 32.00, $days);

        $this->assertTrue(is_array($weather), 'WeatherService: the response is an array');
        $this->assertTrue(count($weather) === $days, 'WeatherService: the response is an array with length = ' . $days);
        for ($i=0; $i < $days; $i++) { 
            $this->assertTrue(isset($weather[$i]), 'WeatherService: there is the key ' . $days . 'in the response');
            $this->assertTrue($weather[$i] === 'not available', 'WeatherService: in the response key ' . $days . ' the value is = "not available"');
        }
    }

    /**
     * It tests WeatherService with no response
     */
    public function testWeatherManagePassingEmptyObject(): void
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        // (3) run some service & test the result
        $WeatherService = $container->get(WeatherService::class);
        $response= new stdClass();
        $days = 2;
        $weather = $response instanceof ResponseInterface ? $WeatherService->manageResponse($response, $days) : $WeatherService->manageResponse(null, $days);

        $this->assertTrue(is_array($weather), 'WeatherService: the response is an array');
        $this->assertTrue(count($weather) === $days, 'WeatherService: the response is an array with length = ' . $days);
        for ($i=0; $i < $days; $i++) { 
            $this->assertTrue(isset($weather[$i]), 'WeatherService: there is the key ' . $days . 'in the response');
            $this->assertTrue($weather[$i] === 'not available', 'WeatherService: in the response key ' . $days . ' the value is = "not available"');
        }
    }

    /**
     * It tests GetWeatherText with empty array
     */
    public function testGetWeatherTextPassingEmptyArray(): void
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        // (3) run some service & test the result
        $WeatherService = $container->get(WeatherService::class);
        $weather = $WeatherService->getWeatherText([[],[]], 0);

        $this->assertTrue(is_string($weather), 'the response is a string');
        $this->assertTrue($weather === 'not available', 'WeatherService:  the weather is "not available"');
        $this->assertTrue($weather === 'not available', 'WeatherService: the weather is "not available"');
    }
}

?>