<?php

namespace App\Command;

use App\Service\MusementService;
use App\Service\WeatherService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * GetCitiesWeather - Verion 1.0 (PHP Version 7.4.9):
 * This class implements a cli command to show a list of all musement location and the forecast for the next 2 days.
 *
 * @note The output is like this: "Processed city [city name] | [weather today] - [wheather tomorrow]"
 *
 * @author Andrea Molteni - molteni.engineer@gmail.com
 */
class GetCitiesWeather extends Command
{
    protected static $defaultName = 'app:get-cities-wheather'; // command name
    protected static $defaultDescription = 'get a list of "Musement" cities and the forecast for the next 2 days'; // command description
    private MusementService $musementService;
    private WeatherService $weatherService;

    /**
     * @param MusementService $MusementService - service to manage the request to musement api
     * @param WeatherService  $weatherService  - service to manage the request to weather api
     */
    public function __construct(MusementService $MusementService, WeatherService $weatherService)
    {
        $this->musementService = $MusementService;
        $this->weatherService = $weatherService;

        parent::__construct();
    }

    /**
     * Help command configuration.
     */
    protected function configure(): void
    {
        $this->setHelp('This command allows you to create a list of "Musement" cities and the forecast for the next 2 days');
    }

    /**
     * @param InputInterface  $input  - to manage input
     * @param OutputInterface $output - to manage output
     *
     * @return int - 0 -> success | 1 -> failure | 2 -> invalid
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->musementService->getCities();

        if (!empty($response['citiesList']) && is_array($response['citiesList'])) {
            $citiesList = $response['citiesList'];
            // response in right format
            foreach ($citiesList as $city) {
                // Prepare method input
                if (!empty($city['lat'])) {
                    $lat = $city['lat'];
                } else {
                    $lat = null;
                }
                if (!empty($city['lon'])) {
                    $lon = $city['lon'];
                } else {
                    $lon = null;
                }
                if (!empty($city['name'])) {
                    $name = $city['name'];
                } else {
                    $name = null;
                }

                $weather = $this->weatherService->getWeather($lat, $lon, 2);

                // manage service response
                if (!empty($weather[0])) {
                    $today = $weather[0];
                } else {
                    $today = 'not available';
                }
                if (!empty($weather[1])) {
                    $tomorrow = $weather[1];
                } else {
                    $tomorrow = 'not available';
                }
                $output->writeln([
                    'Processed city '.$name.' | '.$today.' - '.$tomorrow,
                ]);
            }

            return Command::SUCCESS;
        } else {
            $output->writeln([
                'The service is unavailable',
            ]);

            return Command::FAILURE;
        }
    }
}
