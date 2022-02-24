## author: Andrea Molteni

# Installation

## windows/Mac ambient

### requirement
To use cli command you need to install globally PHP version 7.4 and **composer** on your machine. To check these requirements, run these commands:
- PHP -v 
- composer -v

### necessary commands
To use the script you need to run this command:
- composer install 

## Ambient configuration with Docker:
To configure the ambient you must run these simple commands:
1. build the image: <br>
docker-compose build --no-cache
2. run image to build the container<br>
docker-compose up -d
3. Go into container<br>
docker exec -it -u dev sf5_php bash
4. Go into the folder<br>
cd sf5
5. Download the libraries:<br>
Composer install

Well done!! Everything is ready to run the script
 ([userguide](userguides.md))


