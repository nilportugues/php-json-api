 JSON API Transformers 
=========================================

[![Build Status](https://travis-ci.org/nilportugues/json-api.svg)](https://travis-ci.org/nilportugues/json-api) [![Coverage Status](https://coveralls.io/repos/nilportugues/json-api/badge.svg?branch=master)](https://coveralls.io/r/nilportugues/json-api?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nilportugues/json-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nilportugues/json-api/?branch=master)  [![Latest Stable Version](https://poser.pugx.org/nilportugues/json-api/v/stable)](https://packagist.org/packages/nilportugues/json-api) [![Total Downloads](https://poser.pugx.org/nilportugues/json-api/downloads)](https://packagist.org/packages/nilportugues/json-api) [![License](https://poser.pugx.org/nilportugues/json-api/license)](https://packagist.org/packages/nilportugues/json-api) 

Serializer transformers outputting valid API responses in JSON, JSON API and HAL+JSON API formats.

## Usage: 
Given the following piece of code, defining a Blog Post and some Comments, we'll examine how each transformer works:

```php
$post = new Post(
  new PostId(9),
  'Hello World',
  'Your first post',
  new User(
      new UserId(1),
      'Post Author'
  ),
  [
      new Comment(
          new CommentId(1000),
          'Have no fear, sers, your king is safe.',
          new User(new UserId(2), 'Barristan Selmy'),
          [
              'created_at' => (new DateTime('2015/07/18 12:48:00 -35 minutes'))->format('c'),
              'accepted_at' => (new DateTime('2015/07/19 00:00:00'))->format('c'),
          ]
      ),
  ]
);
```



### JSON 
Given a PHP object, the JSON transformer will transform it to a valid JSON representation. It will preserve the data structure given by the properties of the classes and arrays used by the PHP Object.

**Code:**

```php
use NilPortugues\Serializer\Serializer;
use NilPortugues\Serializer\Transformer\Json\JsonTransformer;

$transformer = new JsonTransformer();
$serializer = new Serializer($transformer);

echo $serializer->serialize($post);
```

**Output:**

```json
{
    "postId": 9,
    "title": "Hello World",
    "content": "Your first post",
    "author": {
        "userId": 1,
        "name": "Post Author"
    },
    "comments": [
        {
            "commentId": 1000,
            "dates": {
                "created_at": "2015-07-18T13:34:55+02:00",
                "accepted_at": "2015-07-18T14:09:55+02:00"
            },
            "comment": "Have no fear, sers, your king is safe.",
            "user": {
                "userId": 2,
                "name": "Barristan Selmy"
            }
        }
    ]
}
```


### JSON API
Given a PHP Object, and a series of mappings, the JSON API transformer will represent the given data following the `http://jsonapi.org` specification.

### HAL+JSON
Given a PHP Object, and a series of mappings, the HAL+JSON API transformer will represent the given data following the `http://stateless.co/hal_specification.html` specification.


