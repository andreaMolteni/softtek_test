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

    protected function configure(): void
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // ... put here the code to create the user

        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable
        $response = MusementService::getCities();
        if($response['statusCode'] !== 200){
            $output->writeln([
                $response['message'],
            ]);
            return Command::FAILURE;
        } else {
            $citiesList = $response['citiesList'];
        }

        array_map(function($element) use ($output){
            $output->writeln([
                'Processed city ' . $element['name'],
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