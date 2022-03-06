<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * MusementServiceTest - Verion 1.0 (PHP Version 7.4.9):
 * This class allows you to test for the GetCitiesWeather cli command class methods.
 *
 * @author Andrea Molteni - molteni.engineer@gmail.com
 */
class getCitiesWeatherCommandTest extends KernelTestCase
{
    private ContainerInterface $testContainer;
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        // boot the Symfony kernel
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:get-cities-wheather');
        $this->commandTester = new CommandTester($command);

        $this->testContainer = static::getContainer();
    }

    /**
     * It tests command when the Musement Services is not 200.
     */
    public function testExecuteWhenMusementServiceNot200(): void
    {
        $response = new MockResponse('test', [
            'http_code' => 504,
        ]);
        $this->testContainer->set('test.Symfony\Contracts\HttpClient\HttpClientInterface', new MockHttpClient($response));

        $this->commandTester->execute([]);

        // the output of the command in the console
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('The service is unavailable', $output, 'The service is unavailable');
    }

    /**
     * It tests command when the Musement Services is not 200.
     */
    public function testExecuteWhenWhenServiceNot200(): void
    {
        $res = json_encode(['badResponse' => ['test']]);
        if (!$res) {
            $res = 'test';
        }
        $response = new MockResponse($res);
        $this->testContainer->set('test.Symfony\Contracts\HttpClient\HttpClientInterface', new MockHttpClient($response));

        $this->commandTester->execute([]);

        $this->commandTester->assertCommandIsSuccessful('The command was successful');

        // the output of the command in the console
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Processed city ', $output, 'The output cointains "Processed city');
        $this->assertStringContainsString(' | ', $output, 'The output cointains " | ');
        $this->assertStringContainsString(' - ', $output, 'The output cointains " - ');
        $this->assertStringContainsString('not available', $output, 'The output cointains "not available');
    }

    /**
     * It tests command GetCitiesWeather in normal conditions.
     */
    public function testExecuteGetCitiesWeather(): void
    {
        $this->commandTester->execute([]);

        $this->commandTester->assertCommandIsSuccessful('The command was successful');

        // the output of the command in the console
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Processed city ', $output, 'The output cointains "Processed city');
        $this->assertStringContainsString(' | ', $output, 'The output cointains " | ');
        $this->assertStringContainsString(' - ', $output, 'The output cointains " - ');
    }
}
