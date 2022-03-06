<?php

namespace App\Tests;

use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * MusementServiceTest - Verion 1.0 (PHP Version 7.4.9):
 * This class allows you to test for the WeatherService class methods.
 *
 * @author Andrea Molteni - molteni.engineer@gmail.com
 */
class WeatherServiceTest extends KernelTestCase
{
    private ContainerInterface $testContainer;

    protected function setUp(): void
    {
        // boot the Symfony kernel
        self::bootKernel();
        // use static::getContainer() to access the service container
        $this->testContainer = static::getContainer();
    }

    /**
     * It tests WeatherService without input.
     */
    public function testWeatherServiceNoInput(): void
    {
        // mocking response
        $WeatherService = $this->testContainer->get(WeatherService::class);
        $days = 2;
        if (is_object($WeatherService) && method_exists($WeatherService, 'getWeather')) {
            $weather = $WeatherService->getWeather();
        } else {
            $weather = [];
        }

        $this->assertTrue(is_array($weather), 'WeatherService: the response is an array');
        $this->assertCount($days, $weather, 'WeatherService: the response is an array with length == '.$days);
        $this->assertContains('not available', $weather, "'weather' array contains 'not available'");
    }

    /**
     * It tests WeatherService with null coordinates.
     */
    public function testWeatherServiceInputNull(): void
    {
        // mocking response
        $WeatherService = $this->testContainer->get(WeatherService::class);
        $days = 2;
        if (is_object($WeatherService) && method_exists($WeatherService, 'getWeather')) {
            $weather = $WeatherService->getWeather(null, null, $days);
        } else {
            $weather = [];
        }

        $this->assertTrue(is_array($weather), 'WeatherService: the response is an array');
        $this->assertCount($days, $weather, 'WeatherService: the response is an array with length == '.$days);
        $this->assertContains('not available', $weather, "'weather' array contains 'not available'");
    }

    /**
     * It tests WeatherService with bad coordinates.
     */
    public function testWeatherServiceBadInput(): void
    {
        // mocking response
        $WeatherService = $this->testContainer->get(WeatherService::class);

        $days = 2;
        if (is_object($WeatherService) && method_exists($WeatherService, 'getWeather')) {
            $weather = $WeatherService->getWeather((float) 'bfsdfhsf', (float) 'gasksjdhf', $days);
        } else {
            $weather = [];
        }

        $this->assertTrue(is_array($weather), 'WeatherService: the response is an array');
        $this->assertCount($days, $weather, 'WeatherService: the response is an array with length == '.$days);
        $this->assertContains('not available', $weather, "'weather' array contains 'not available'");
    }

    /**
     * It tests WeatherService in normal conditions.
     */
    public function testWeatherServiceNormal(): void
    {
        // mocking response
        $WeatherService = $this->testContainer->get(WeatherService::class);
        $days = 2;
        if (is_object($WeatherService) && method_exists($WeatherService, 'getWeather')) {
            $weather = $WeatherService->getWeather(48.138, 32.00, $days);
        } else {
            $weather = [];
        }

        $this->assertTrue(is_array($weather), 'WeatherService: the response is an array');
        $this->assertCount($days, $weather, 'WeatherService: the response is an array with length == '.$days);
        $this->assertNotContains('not available', $weather, "'weather' array not contains 'not available'");
    }

    /**
     * It tests WeatherService passing more than 2 days.
     */
    public function testWeatherServiceMoreDays(): void
    {
        // mocking response
        $WeatherService = $this->testContainer->get(WeatherService::class);
        $days = 3;
        if (is_object($WeatherService) && method_exists($WeatherService, 'getWeather')) {
            $weather = $WeatherService->getWeather(48.138, 32.00, $days);
        } else {
            $weather = [];
        }

        $this->assertTrue(is_array($weather), 'WeatherService: the response is an array');
        $this->assertCount($days, $weather, 'WeatherService: the response is an array with length == '.$days);
        $this->assertNotContains('not available', $weather, "'weather' array not contains 'not available'");
    }

    /**
     * It tests WeatherService passing less than 2 days.
     */
    public function testWeatherServiceLessDays(): void
    {
        // mocking response
        $WeatherService = $this->testContainer->get(WeatherService::class);
        $days = 1;
        if (is_object($WeatherService) && method_exists($WeatherService, 'getWeather')) {
            $weather = $WeatherService->getWeather(48.138, 32.00, $days);
        } else {
            $weather = [];
        }

        $this->assertTrue(is_array($weather), 'WeatherService: the response is an array');
        $this->assertCount($days, $weather, 'WeatherService: the response is an array with length == '.$days);
        $this->assertNotContains('not available', $weather, "'weather' array not contains 'not available'");
    }

    /**
     * It tests WeatherService passing less than 3 days.
     */
    public function testWeatherServiceTooManyDays(): void
    {
        // mocking response
        $WeatherService = $this->testContainer->get(WeatherService::class);
        $days = 10;
        if (is_object($WeatherService) && method_exists($WeatherService, 'getWeather')) {
            $weather = $WeatherService->getWeather(48.138, 32.00, $days);
        } else {
            $weather = [];
        }

        $this->assertTrue(is_array($weather), 'WeatherService: the response is an array');
        $this->assertCount($days, $weather, 'WeatherService: the response is an array with length == '.$days);
        $this->assertContains('not available', $weather, "'weather' array contains 'not available'");
    }

    /**
     * It tests WeatherService with bad request.
     */
    public function testWeatherServiceBadUrl(): void
    {
        // mocking response
        $days = 2;
        $response = new MockResponse('test', [
            'http_code' => 400,
        ]);
        $this->testContainer->set('test.Symfony\Contracts\HttpClient\HttpClientInterface', new MockHttpClient($response));
        $WeatherService = $this->testContainer->get(WeatherService::class);
        if (is_object($WeatherService) && method_exists($WeatherService, 'getWeather')) {
            $weather = $WeatherService->getWeather(48.138, 32.00, $days);
        } else {
            $weather = [];
        }

        $this->assertTrue(is_array($weather), 'WeatherService: the response is an array');
        $this->assertCount($days, $weather, 'WeatherService: the response is an array with length == '.$days);
        $this->assertContains('not available', $weather, "'weather' array contains 'not available'");
    }

    /**
     * It tests WeatherService in case of failure.
     */
    public function testWeatherServiceFailure(): void
    {
        // mocking response
        $days = 2;
        $body = function () {
            yield 'simulating';
            // empty strings are turned into timeouts so that they are easy to test
            yield '';
            yield 'server failure';
        };
        $response = new MockResponse($body());
        $this->testContainer->set('test.Symfony\Contracts\HttpClient\HttpClientInterface', new MockHttpClient($response));
        $WeatherService = $this->testContainer->get(WeatherService::class);
        if (is_object($WeatherService) && method_exists($WeatherService, 'getWeather')) {
            $weather = $WeatherService->getWeather(48.138, 32.00, $days);
        } else {
            $weather = [];
        }

        $this->assertTrue(is_array($weather), 'WeatherService: the response is an array');
        $this->assertCount(0, $weather, 'WeatherService: the response is an array with length == '.$days);
        $this->assertEmpty($weather, "'weather' array contains 'not available'");
    }

    /**
     * It tests WeatherService in case it responses with a bad array.
     */
    public function testWeatherServiceBadResponse(): void
    {
        // mocking response
        $days = 2;
        $res = json_encode(['badResponse' => ['test']]);
        if (!$res) {
            $res = 'test';
        }
        $response = new MockResponse($res);
        $this->testContainer->set('test.Symfony\Contracts\HttpClient\HttpClientInterface', new MockHttpClient($response));
        $WeatherService = $this->testContainer->get(WeatherService::class);
        if (is_object($WeatherService) && method_exists($WeatherService, 'getWeather')) {
            $weather = $WeatherService->getWeather(48.138, 32.00, $days);
        } else {
            $weather = [];
        }

        $this->assertTrue(is_array($weather), 'WeatherService: the response is an array');
        $this->assertCount($days, $weather, 'WeatherService: the response is an array with length == '.$days);
        $this->assertContains('not available', $weather, "'weather' array contains 'not available'");
    }
}
