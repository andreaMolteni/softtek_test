## author: Andrea Molteni

# Notes on choices

## **Framework choice**
To implement the cli command, I decided to use the skeleton symphony framework without web app components in order to have well-organized and light sw structure.
In this way I could concentrate only on the core code.

## **Classes design**

### **Otherview**
The cli command is implemented by **GetCitiesWeather** class that extends the class Command provided by symphony. This class uses two Services **MusementService** and **WeatherService**.
MusementService makes a request to musement api and it gives back the list of all the cities where TUI Musement is active. 
WeatherService makes requests to weather api in order to obtain the weather forecast for each city.
I chose to separate these services in two classes in order to make their reuse possible, and to test the services more easily.
The source code is in [src folder](../src/). [Here](../tests/) the tests folder.

### **MusementService**
This class has these methods:
- getCities
- manageResponse

The class attributes are:
- params
- client
- url

The attribute url allows to get the url endpoint of the Musement api from outside to make the class independent from future changes([ view config file](../config/services.yaml)).
I chose to manage the url throw the class attribute in order to test the class behaviour inserting wrong urls.
If the api response isn’t 200, the method returns the relative error message and the empty array of the cities. 
The managed codes are 200, 404 and 503.

### **WeatherService**
This class has these methods:
- getWeather
- manageResponse
- getWeatherText

The class attributes are:
- params
- client
- apiKey
- apiBaseUrl

Both url and key of the weather api are taken from outside to make the class independent from future changes. ([view config file](../config/services.yaml))
Also in this case, I chose to manage the url and key throw the class attributes in order to test the class behaviour inserting wrong urls and key.

### getWeather
This method accepts the geographic coordinates of a locality and the number of days we want to know the forecasts of. The method returns an array with the weather forecasts for each required day.
Even if forecasts for the current and following day for each locality are requested, i extended the method in order to manage the forecasts for more days for future advancements.
To obtain the weather forecasts from the API response, getWeather recalls the **manageResponse** method.

### manageResponse
The response method elaborates the response if the code is 200 and recovers the textual description of the locality weather in the following days. If the response code is not 200, it returns a “not Available” array. manageResponse in order to extract the textual description from the api response array calls **getWeatherText** method. 

### getWeatherText
It extracts the weather forecasts for some following days from the response array in text format
If in the response array there isn’t the data, it returns “not avalaible”

### **Static analyze**
For the static code analysis PHPStan library has been used.

### **TESTING**
Concerning the testing, three classes have been developed. The first one performs the test of cli command behaviour, the second one is for MusementService and the last one for WeatherService.  
**For each test there are several asserts.**

### getCitiesWeatherCommandTest
- tests getCitiesWeatherCommand behaviour

### MusementServiceTest
- tests MusemenService in normal conditions.  
- tests MusemenService in case of the response in not 200

### WeathertServiceTest:
- tests WeatherService without input  
- tests WeatherService with null coordinates  
- tests WeatherService with bad coordinates   
- tests WeatherService in normal conditions  
- tests WeatherService with bad url  
- tests WeatherService with bad key  
- tests WeatherService with no response  
- It tests GetWeatherText with empty array  


[Docs summary](../README.md)








