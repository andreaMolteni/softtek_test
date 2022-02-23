<?php
namespace App\Tests;

use App\Service\MusementService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * MusementServiceTest - Verion 1.0 (PHP Version 7.4.9):
 * This class allows you to test for the MusementService class methods
 * 
 * @author Andrea Molteni - molteni.engineer@gmail.com
 */
class MusementServiceTest extends KernelTestCase
{
    /**
     * It tests MusemenService in normal conditions
     */
    public function testMusementService(): void
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        // (3) run some service & test the result
        $MusementService = $container->get(MusementService::class);
        $cities = $MusementService->getCities();

        $this->assertTrue(is_array($cities), 'MusementService: the response is an array');
        $this->assertTrue(count($cities) > 0, 'MusementService: the response is an array with length > 0');
        $this->assertTrue(isset($cities['statusCode']), 'MusementService: there is the key "statusCode" in the response');
        $this->assertTrue(is_int($cities['statusCode']), 'MusementService: the "statusCode" is int');
        $this->assertTrue($cities['statusCode'] === 200, 'MusementService: the "statusCode" is 200');
        $this->assertTrue(isset($cities['message']), 'MusementService: there is the key "message" in the response');
        $this->assertTrue(is_string($cities['message']), 'MusementService: the "message" in string');
        $this->assertTrue($cities['message'] === 'Returned when successful','MusementService: the "message" is "Returned when successful"');
        $this->assertTrue(isset($cities['citiesList']), 'MusementService: there is the key "citiesList" in the response');
        $this->assertTrue(is_array($cities['citiesList']), 'MusementService: the "citiesList" in an array');
        $this->assertTrue(count($cities['citiesList']) > 0, 'MusementService: the "citiesList" length is > 0');

        $checkForEachCities = true;
        foreach ($cities['citiesList'] as $city) {
            if (empty($city['name']) || empty($city['lat']) || empty($city['lon'])){
                $checkForEachCities = false;
                break;
            }
        }
        
        $this->assertTrue($checkForEachCities, 'MusementService: for each "citiesList" the key "name", "lat" and "lon" are valorized');
    }

    /**
     * It tests MusemenService in case of the response in not 200
     */
    public function testMusementService400(): void
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();
        // (3) run some service & test the result
        $MusementService = $container->get(MusementService::class);
        $MusementService->url = 'https://api.musement.com/api/v3/cities/gxfgjhxgfj';
        $cities = $MusementService->getCities();

        $this->assertTrue(is_array($cities), 'MusementService: the response is an array');
        $this->assertTrue(count($cities) > 0, 'MusementService: the response is an array with length > 0');
        $this->assertTrue(isset($cities['statusCode']), 'MusementService: there is the key "statusCode" in the response');
        $this->assertTrue(is_int($cities['statusCode']), 'MusementService: the "statusCode" is int');
        $this->assertTrue($cities['statusCode'] !== 200, 'MusementService: the "statusCode" is\'t 200');
        $this->assertTrue(isset($cities['message']), 'MusementService: there is the key "message" in the response');
        $this->assertTrue(is_string($cities['message']), 'MusementService: the "message" in string');
        $this->assertTrue($cities['message'] === 'No resource found','MusementService: the "message" is "No resource found"');
        $this->assertTrue(isset($cities['citiesList']), 'MusementService: there is the key "citiesList" in the response');
        $this->assertTrue(is_array($cities['citiesList']), 'MusementService: the "citiesList" in an array');
        $this->assertTrue(count($cities['citiesList']) === 0, 'MusementService: the "citiesList" length is 0');
    }
}

?>
