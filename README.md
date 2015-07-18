# API Transformers in JSON

Serializer transformers outputting valid API responses in JSON, JSON API and HAL+JSON API formats.

## JSON 
Given a PHP object, the JSON transformer will transform it to a valid JSON representation. It will preserve the data structure given by the properties of the classes and arrays used by the PHP Object.

## JSON API
Given a PHP Object, and a series of mappings, the JSON API transformer will represent the given data following the `http://jsonapi.org` specification.

## HAL+JSON
Given a PHP Object, and a series of mappings, the HAL+JSON API transformer will represent the given data following the `http://stateless.co/hal_specification.html` specification.
