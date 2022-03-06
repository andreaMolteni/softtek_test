<?php

namespace App\Tests;

use App\Service\MusementService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * MusementServiceTest - Verion 1.0 (PHP Version 7.4.9):
 * This class allows you to test for the MusementService class methods.
 *
 * @author Andrea Molteni - molteni.engineer@gmail.com
 */
class MusementServiceTest extends KernelTestCase
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
     * It tests MusemenService in normal conditions.
     */
    public function testMusementService(): void
    {
        $MusementService = $this->testContainer->get(MusementService::class);
        if (is_object($MusementService) && method_exists($MusementService, 'getCities')) {
            $cities = $MusementService->getCities();
        } else {
            $cities = [];
        }

        $this->assertTrue(is_array($cities), 'MusementService: the response is an array');
        $this->assertGreaterThan(0, count($cities), 'MusementService: the response is an array with length > 0');
        $this->assertArrayHasKey('statusCode', $cities, 'MusementService: there is the key "statusCode" in the response');
        $this->assertTrue(is_int($cities['statusCode']), 'MusementService: the "statusCode" is int');
        $this->assertEquals(200, $cities['statusCode'], 'MusementService: the "statusCode" is 200');
        $this->assertArrayHasKey('message', $cities, 'MusementService: there is the key "message" in the response');
        $this->assertTrue(is_string($cities['message']), 'MusementService: the "message" in string');
        $this->assertSame('Success', $cities['message'], 'MusementService: the "message" is "Returned when successful"');
        $this->assertArrayHasKey('citiesList', $cities, 'MusementService: there is the key "citiesList" in the response');
        $this->assertTrue(is_array($cities['citiesList']), 'MusementService: the "citiesList" in an array');
        $this->assertGreaterThan(0, count($cities['citiesList']), 'MusementService: the "citiesList" length is > 0');
    }

    /**
     * It tests MusemenService in case of the http response code is 404.
     */
    public function testMusementServiceWhenResponseIs404(): void
    {
        // mocking response
        $response = new MockResponse('test', [
            'http_code' => 404,
        ]);
        $this->testContainer->set('test.Symfony\Contracts\HttpClient\HttpClientInterface', new MockHttpClient($response));
        $MusementService = $this->testContainer->get(MusementService::class);
        if (is_object($MusementService) && method_exists($MusementService, 'getCities')) {
            $cities = $MusementService->getCities();
        } else {
            $cities = [];
        }

        $this->assertTrue(is_array($cities), 'MusementService: the response is an array');
        $this->assertGreaterThan(0, count($cities), 'MusementService: the response is an array with length > 0');
        $this->assertArrayHasKey('statusCode', $cities, 'MusementService: there is the key "statusCode" in the response');
        $this->assertTrue(is_int($cities['statusCode']), 'MusementService: the "statusCode" is int');
        $this->assertEquals(404, $cities['statusCode'], 'MusementService: the "statusCode" is 404');
        $this->assertArrayHasKey('message', $cities, 'MusementService: there is the key "message" in the response');
        $this->assertTrue(is_string($cities['message']), 'MusementService: the "message" in string');
        $this->assertSame('No resource found', $cities['message'], 'MusementService: the "message" is "No resource found"');
        $this->assertArrayHasKey('citiesList', $cities, 'MusementService: there is the key "citiesList" in the response');
        $this->assertTrue(is_array($cities['citiesList']), 'MusementService: the "citiesList" in an array');
        $this->assertEmpty($cities['citiesList'], 'MusementService: the "cities[citiesList]" is empty');
    }

    /**
     * It tests MusemenService in case of the http response code is 503.
     */
    public function testMusementServiceWhenResponseIs503(): void
    {
        // mocking response
        $response = new MockResponse('test', [
            'http_code' => 503,
        ]);
        $this->testContainer->set('test.Symfony\Contracts\HttpClient\HttpClientInterface', new MockHttpClient($response));
        $MusementService = $this->testContainer->get(MusementService::class);
        if (is_object($MusementService) && method_exists($MusementService, 'getCities')) {
            $cities = $MusementService->getCities();
        } else {
            $cities = [];
        }

        $this->assertTrue(is_array($cities), 'MusementService: the response is an array');
        $this->assertGreaterThan(0, count($cities), 'MusementService: the response is an array with length > 0');
        $this->assertArrayHasKey('statusCode', $cities, 'MusementService: there is the key "statusCode" in the response');
        $this->assertTrue(is_int($cities['statusCode']), 'MusementService: the "statusCode" is int');
        $this->assertEquals(503, $cities['statusCode'], 'MusementService: the "statusCode" is 503');
        $this->assertArrayHasKey('message', $cities, 'MusementService: there is the key "message" in the response');
        $this->assertTrue(is_string($cities['message']), 'MusementService: the "message" in string');
        $this->assertSame('Service unavailable', $cities['message'], 'MusementService: the "message" is "Service unavailable"');
        $this->assertArrayHasKey('citiesList', $cities, 'MusementService: there is the key "citiesList" in the response');
        $this->assertTrue(is_array($cities['citiesList']), 'MusementService: the "citiesList" in an array');
        $this->assertEmpty($cities['citiesList'], 'MusementService: the "cities[citiesList]" is empty');
    }

    /**
     * It tests MusemenService in case of the http response code is not 200 or 404 or 503.
     */
    public function testMusementServiceWhenResponseIsXXX(): void
    {
        // mocking response
        $response = new MockResponse('test', [
            'http_code' => 500,
        ]);
        $this->testContainer->set('test.Symfony\Contracts\HttpClient\HttpClientInterface', new MockHttpClient($response));
        $MusementService = $this->testContainer->get(MusementService::class);
        if (is_object($MusementService) && method_exists($MusementService, 'getCities')) {
            $cities = $MusementService->getCities();
        } else {
            $cities = [];
        }

        $this->assertTrue(is_array($cities), 'MusementService: the response is an array');
        $this->assertGreaterThan(0, count($cities), 'MusementService: the response is an array with length > 0');
        $this->assertArrayHasKey('statusCode', $cities, 'MusementService: there is the key "statusCode" in the response');
        $this->assertTrue(is_int($cities['statusCode']), 'MusementService: the "statusCode" is int');
        $this->assertNotEquals(200, $cities['statusCode'], 'MusementService: the "statusCode" is not 200');
        $this->assertNotEquals(503, $cities['statusCode'], 'MusementService: the "statusCode" is not 503');
        $this->assertNotEquals(404, $cities['statusCode'], 'MusementService: the "statusCode" is not 404');
        $this->assertArrayHasKey('message', $cities, 'MusementService: there is the key "message" in the response');
        $this->assertTrue(is_string($cities['message']), 'MusementService: the "message" in string');
        $this->assertSame('Internal Server Error', $cities['message'], 'MusementService: the "message" is "Internal Server Error"');
        $this->assertArrayHasKey('citiesList', $cities, 'MusementService: there is the key "citiesList" in the response');
        $this->assertTrue(is_array($cities['citiesList']), 'MusementService: the "citiesList" in an array');
        $this->assertEmpty($cities['citiesList'], 'MusementService: the "cities[citiesList]" is empty');
    }

    /**
     * It tests MusemenService in case of failure.
     */
    public function testMusementServiceWhenResponseFailure(): void
    {
        // mocking response
        $body = function () {
            yield 'simulating';
            // empty strings are turned into timeouts so that they are easy to test
            yield '';
            yield 'server failure';
        };

        $response = new MockResponse($body());
        $this->testContainer->set('test.Symfony\Contracts\HttpClient\HttpClientInterface', new MockHttpClient($response));
        $MusementService = $this->testContainer->get(MusementService::class);
        if (is_object($MusementService) && method_exists($MusementService, 'getCities')) {
            $cities = $MusementService->getCities();
        } else {
            $cities = [];
        }

        $this->assertTrue(is_array($cities), 'MusementService: the response is an array');
        $this->assertGreaterThan(0, count($cities), 'MusementService: the response is an array with length > 0');
        $this->assertArrayHasKey('statusCode', $cities, 'MusementService: there is the key "statusCode" in the response');
        $this->assertTrue(is_int($cities['statusCode']), 'MusementService: the "statusCode" is int');
        $this->assertEquals(1001, $cities['statusCode'], 'MusementService: the "statusCode" is not 1001');
        $this->assertArrayHasKey('message', $cities, 'MusementService: there is the key "message" in the response');
        $this->assertTrue(is_string($cities['message']), 'MusementService: the "message" in string');
        $this->assertSame('Service unavailable', $cities['message'], 'MusementService: the "message" is "Service unavailable"');
        $this->assertArrayHasKey('citiesList', $cities, 'MusementService: there is the key "citiesList" in the response');
        $this->assertTrue(is_array($cities['citiesList']), 'MusementService: the "citiesList" in an array');
        $this->assertEmpty($cities['citiesList'], 'MusementService: the "cities[citiesList]" is empty');
    }
}
