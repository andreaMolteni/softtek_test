<?php
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use App\Service\MusementService;

/**
 * MusementServiceTest - Verion 1.0 (PHP Version 7.4.9):
 * This class allows you to test for the GetCitiesWeather cli command class methods
 * 
 * @author Andrea Molteni - molteni.engineer@gmail.com
 */
class getCitiesWeatherCommandTest extends KernelTestCase
{
    /**
     * It tests command GetCitiesWeather in normal conditions
     */
    public function testExecute(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:get-cities-wheather');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful('The command was successful');

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Processed city ', $output, 'The output cointains "Processed city');
    }

    /**
     * It tests command GetCitiesWeather iwhen endpoint not responds 200
     */
    public function testExecuteNotResponseFromServices(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:get-cities-wheather');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful('The command was successful');

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Processed city ', $output, 'The output cointains "Processed city');
        $this->assertStringContainsString(' | ', $output, 'The output cointains " | ');
        $this->assertStringContainsString(' - ', $output, 'The output cointains " - ');
    }
}

?>
