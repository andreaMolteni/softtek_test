## author: Andrea Molteni

# User Guide

## cli command
The command to run the script is:  
**php bin/console app:get-cities-wheather**

## Static code analysis
The develop environment includes also the static code analysis library **PHPStan**
It’s possible to run following commands to carry out the analysis:  
**vendor/bin/phpstan analyse -l # src tests**  
The **-l #** option allows to set the analysis level **#** from 1 to 9

## Unit tests
The environment includes unit tests for the class that implements cli command and the class for MusementService and WeatherService  the services.
To perform all tests run:
- vendor/bin/phpunit


To perform the tests for single class run:
- **vendor/bin/phpunit tests/ getCitiesWeatherCommandTest.php**
- **vendor/bin/phpunit tests/MusementServiceTest.php**
- **vendor/bin/phpunit tests/WeatherServiceTest.php**

[Docs summary](../README.md)



