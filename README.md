 JSON API Transformers 
=========================================

[![Build Status](https://travis-ci.org/nilportugues/json-api.svg)](https://travis-ci.org/nilportugues/json-api) [![Coverage Status](https://coveralls.io/repos/nilportugues/json-api/badge.svg?branch=master)](https://coveralls.io/r/nilportugues/json-api?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nilportugues/json-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nilportugues/json-api/?branch=master)  [![Latest Stable Version](https://poser.pugx.org/nilportugues/json-api/v/stable)](https://packagist.org/packages/nilportugues/json-api) [![Total Downloads](https://poser.pugx.org/nilportugues/json-api/downloads)](https://packagist.org/packages/nilportugues/json-api) [![License](https://poser.pugx.org/nilportugues/json-api/license)](https://packagist.org/packages/nilportugues/json-api) 

Serializer transformers outputting valid API responses in JSON, JSON API and HAL+JSON API formats.

## JSON 
Given a PHP object, the JSON transformer will transform it to a valid JSON representation. It will preserve the data structure given by the properties of the classes and arrays used by the PHP Object.

## JSON API
Given a PHP Object, and a series of mappings, the JSON API transformer will represent the given data following the `http://jsonapi.org` specification.

## HAL+JSON
Given a PHP Object, and a series of mappings, the HAL+JSON API transformer will represent the given data following the `http://stateless.co/hal_specification.html` specification.
