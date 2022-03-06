## author: Andrea Molteni

# Api design

## Overview
We want to realize some api to offer a service that allows to receive weather forecasts about a given city for the current day, the following one or for a certain date.
The requests can be: 
- What's the weather in [city] today ?
- What will it be the weather in [city] tomorrow ?
- What was the weather in [city] on [day] ?

## Path description
The paths that carry out the requests must be the following:
- /cities/{cityId}/weather/today
- /cities/{cityId}/weather/tomorrow
- /cities/{cityId}/weather/{day}

### **HTTP request methods**
The verb **GET** must be used to make the requests . There are no other methods because the service is only to recover information.

### **Parameters**:
- ### {Version}
    name: "X-Musement-Version"  
    in: "header"  
    required: false  
    schema: {  
       type: "string"  
    }  
- ### {Accept-Language}
    name: "Accept-Language"  
    in: "header"  
    required: false  
    schema: {  
       type: "string"  
       default: "en-US"  
    }  
- ### {cityId}
    name: "cityId"  
    in: "path"  
    description: "City identifier"  
    required: true  
    schema: {  
       type: "integer"  
    },
- ### {day}
    name: "cityId"  
    in: "path"  
    description: "date in the format YYYY-MM-DD - it must not exceed the current day of 3 days"  
    required: true   
    schema: {  
       type: "string",  
       format: "date"  
    },

### **Responses**
The response format for the three requests is the same.. Their description follows
- 200:  
    description: Returned when successful  
    content:  
    application/json  
           {field: temp_c,	type: decimal,	description: 	Temperature in celsius}
           {field: temp_f,	type: 	decimal,	description: 	Temperature in fahrenheit}
           {field: condition:text,	type: 	string,	description: 	Weather condition text}
           {field: condition:icon,	type: 	string,	description: 	Weather icon url}
           {field: condition:code,	type: 	int,	description: 	Weather condition unique code.}
           {field: wind_mph,	type: 	decimal,	description: 	Wind speed in miles per hour}
           {field: wind_kph,	type: 	decimal,	description: 	Wind speed in kilometer per hour}
           {field: wind_degree,	type: 	int,	description: 	Wind direction in degrees}
           {field: wind_dir,	type: 	string,	description: 	Wind direction as 16 point compass. e.g.: NSW}
           {field: pressure_mb,	type: 	decimal,	description: 	Pressure in millibars}
           {field: pressure_in,	type: 	decimal,	description: 	Pressure in inches}
           {field: precip_mm,	type: 	decimal,	description: 	Precipitation amount in millimeters}
           {field: humidity,	type: 	int,	description: 	Humidity as percentage}
           {field: cloud,	type: 	int,	description: 	Cloud cover as percentage}
           {field: gust_mph,	type: 	decimal,	description: 	Wind gust in miles per hour}
           {field: gust_kph,	type: 	decimal,	description: 	Wind gust in kilometer per hour}
- 400:  
    description: Returned when bad request. 
- 404:  
    description: Returned when resource is not found
- 503:  
    description: Service unavailable

## note sull'implementazione
1. The api must predict a control of http request method
2. if the parameters related to version and language are in the header, they must be read. otherwise, the default language is 'EN-US'
3. the api must implement all the described response types with the relating codes

## **For more details open the open the [json specs swagger](cities-weather-swagger.json)**

[Docs summary](../README.md)