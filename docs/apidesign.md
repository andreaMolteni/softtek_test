## author: Andrea Molteni

# Api design

## Overview
We want to realize some api to offer a service that allows to receive weather forecasts about a given city for the current day, the following one or for a certain date.
The requests can be: 
- What's the weather in [city] today ?
- What will it be the weather in [city] tomorrow ?
- What was the weather in [city] on [day] ?

## Descrizione dei path
Le rotte per i servizi richiesti devo essere i seguenti:
- /cities/{cityId}/weather/today
- /cities/{cityId}/weather/tomorrow
- /cities/{cityId}/weather/{day}

### **HTTP request methods**
the verb **GET** must be used to make the requests . There are no other methods because the service is only to recover information.

### **Parameters**:
- ### {Version}
    name: "X-Musement-Version"<br>
    in: "header"<br>
    required: false<br>
    schema: {<br>
       type: "string"<br>
    }<br>
- ### {Accept-Language}
    name: "Accept-Language"<br>
    in: "header"<br>
    required: false<br>
    schema: {<br>
       type: "string"<br>
       default: "en-US"<br>
    }<br>
- ### {cityId}
    name: "cityId"<br>
    in: "path"<br>
    description: "City identifier" <br>
    required: true <br> 
    schema: {<br> 
       type: "integer"<br> 
    },
- ### {day}
    name: "cityId"<br>
    in: "path"<br>
    description: "date in the format YYYY-MM-DD - it must not exceed the current day of 3 days" <br>
    required: true <br> 
    schema: {<br>
       type: "string",<br>
       format: "date"<br>
    },

### **responses**
Il ormato della risposta per le tre richieste è identica. Di seguito la descrizione
- 200: <br>
    description: Returned when successful<br>
    content:<br>
    application/json<br>
           {field: temp_c,	type: decimal,	description: 	Temperature in celsius}<br>
           {field: temp_f,	type: 	decimal,	description: 	Temperature in fahrenheit}<br>
           {field: condition:text,	type: 	string,	description: 	Weather condition text}<br>
           {field: condition:icon,	type: 	string,	description: 	Weather icon url}<br>
           {field: condition:code,	type: 	int,	description: 	Weather condition unique code.}<br>
           {field: wind_mph,	type: 	decimal,	description: 	Wind speed in miles per hour}<br>
           {field: wind_kph,	type: 	decimal,	description: 	Wind speed in kilometer per hour}<br>
           {field: wind_degree,	type: 	int,	description: 	Wind direction in degrees}<br>
           {field: wind_dir,	type: 	string,	description: 	Wind direction as 16 point compass. e.g.: NSW}<br>
           {field: pressure_mb,	type: 	decimal,	description: 	Pressure in millibars}<br>
           {field: pressure_in,	type: 	decimal,	description: 	Pressure in inches}<br>
           {field: precip_mm,	type: 	decimal,	description: 	Precipitation amount in millimeters}<br>
           {field: humidity,	type: 	int,	description: 	Humidity as percentage}<br>
           {field: cloud,	type: 	int,	description: 	Cloud cover as percentage}<br>
           {field: gust_mph,	type: 	decimal,	description: 	Wind gust in miles per hour}<br>
           {field: gust_kph,	type: 	decimal,	description: 	Wind gust in kilometer per hour}<br>
- 400: <br>
    description: Returned when bad request. 
- 404: <br>
    description: Returned when resource is not found
- 503: <br>
    description: Service unavailable

## note sull'implementazione
1. Le api devono prevedere un controllo del HTTP request methods
2. The api must predict a control of http request method
3. if the parameters related to version and language are in the header, they must be read. otherwise, the default language is 'EN-US'
4. the api must implement all the described response types with the relating codes

## **For more details open the open the [json specs swagger](cities-weather-swagger.json)**