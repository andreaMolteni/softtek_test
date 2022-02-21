<?php

namespace App\Command;

use App\Service\MusementService;
use App\Service\WeatherService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class GetCitiesWeather extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:get-cities-wheather';
    private $musementService;
    private $weatherService;

    public function __construct(MusementService $MusementService, WeatherService $weatherService)
    {
        $this->musementService = $MusementService;
        $this->weatherService = $weatherService;

        parent::__construct();
    }

    protected function configure(): void
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->musementService->getCities();
        if(!empty($response['statusCode']) && $response['statusCode'] !== 200){
            $output->writeln([
                $response['message'],
            ]);
            return Command::FAILURE;
        } else {
            $citiesList = $response['citiesList'];
        }

        array_map(function($element) use ($output){
            $weather = $this->weatherService->getWeather((float)$element['lat'], (float)$element['lon'], 2);
            $output->writeln([
                'Processed city ' . $element['name'] . ' | ' . $weather['today'] . ' - ' . $weather['tomorrow'],
            ]);
        }, $citiesList );
        
        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}

?>