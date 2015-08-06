 JSON API Transformers 
=========================================

[![Build Status](https://travis-ci.org/nilportugues/json-api-transformers.svg)](https://travis-ci.org/nilportugues/json-api-transformers) [![Coverage Status](https://coveralls.io/repos/nilportugues/json-api/badge.svg?branch=master&service=github)](https://coveralls.io/github/nilportugues/json-api?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nilportugues/json-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nilportugues/json-api/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/e39e4c0e-a402-495b-a763-6e0482e2083d/mini.png)](https://insight.sensiolabs.com/projects/e39e4c0e-a402-495b-a763-6e0482e2083d) [![Latest Stable Version](https://poser.pugx.org/nilportugues/json-api/v/stable)](https://packagist.org/packages/nilportugues/json-api) [![Total Downloads](https://poser.pugx.org/nilportugues/json-api/downloads)](https://packagist.org/packages/nilportugues/json-api) [![License](https://poser.pugx.org/nilportugues/json-api/license)](https://packagist.org/packages/nilportugues/json-api) 

Serializer transformers outputting valid API (PSR-7) Responses in **JSON**, **JSend**, **JSON API** and **HAL+JSON** API formats.


* [1. Purpose](#1-purpose)
* [2. Features](#2-features)
* [3. Installation](#3-installation)
* [4. Usage](#4-usage)
  * [4.1. JSON](#41-json)
  * [4.2. JSend](#42-jsend)
  * [4.3. JSON API](#43-json-api)
  * [4.4. HAL+JSON](#44-haljson)
* [5. Quality Code](#5-quality-code)
* [6. Questions?](#6-questions)
* [7. Author](#7-author)


## 1. Purpose

Web APIs are quick becoming the centerpiece of today entreprises and business, big or small, and allow us **to connect anything and everything**. By exposing data and application functionality to external applications **any organization can remake its business into an extensible platform**. 

API are a mandated requirement for today's modern enterprise, **enabling interactions with customers** over new mobility and social channels and evolving ways to **reach new customers through partner and third party applications**.

The provided **JSON API Transformers package** will allow you to **accomplish this goal in no time**.

## 2. Features

- Transform to JSON, JSend, JSONAPI and HAL+JSON format using mappings.
- Supports nested classes, no matter its complexity.
- Allows renaming of classes and properties.
- Allows hiding properties.
- Completely decoupled from any framework.
- Fully tested and high quality code.
- Actively supported and maintained.

## 3. Installation
The recommended way to install the  JSON API Transformers is through [Composer](http://getcomposer.org). Run the following command to install it:

```sh
php composer.phar require nilportugues/json-api
```

## 4. Usage
Given the following piece of code, defining a Blog Post and some Comments:

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
              'created_at' => (new DateTime('2015/07/18 12:13:00'))->format('c'),
              'accepted_at' => (new DateTime('2015/07/19 00:00:00'))->format('c'),
          ]
      ),
  ]
);
```

An a Mapping array for all the involved classes:

```php
$mappings = [
    [
        'class' => Post::class,
        'alias' => 'Message',
        'aliased_properties' => [
            'title' => 'headline',
            'content' => 'body',
        ],
        'hide_properties' => [],
        'id_properties' => [
            'postId',
        ],
        'urls' => [
            'self' => 'http://example.com/posts/{postId}',
        ],
        'relationships' => [
            'author' => [
                'related' => 'http://example.com/posts/{postId}/author',
                'self' => 'http://example.com/posts/{postId}/relationships/author',
            ]
        ],
    ],
    [
        'class' => PostId::class,
        'alias' => '',
        'aliased_properties' => [],
        'hide_properties' => [],
        'id_properties' => [
            'postId',
        ],
        'urls' => [
            'self' => 'http://example.com/posts/{postId}',
        ]
        'relationships' => [
            'comment' => [
                'self' => 'http://example.com/posts/{postId}/relationships/comments',
            ]
        ],
    ],
    [
        'class' => User::class,
        'alias' => '',
        'aliased_properties' => [],
        'hide_properties' => [],
        'id_properties' => [
            'userId',
        ],
        'urls' => [
            'self' => 'http://example.com/users/{userId}',
        ],
    ],
    [
        'class' => UserId::class,
        'alias' => '',
        'aliased_properties' => [],
        'hide_properties' => [],
        'id_properties' => [
            'userId',
        ],
        'urls' => [
            'self' => 'http://example.com/users/{userId}',
        ],
    ],
    [
        'class' => Comment::class,
        'alias' => '',
        'aliased_properties' => [],
        'hide_properties' => [],
        'id_properties' => [
            'commentId',
        ],
        'urls' => [
            'self' => 'http://example.com/comments/{commentId}',
        ],
        'relationships' => [
            'post' => [
                'self' => 'http://example.com/posts/{postId}/relationships/comments',
            ],
        ],
    ],
    [
        'class' => CommentId::class,
        'alias' => '',
        'aliased_properties' => [],
        'hide_properties' => [],
        'id_properties' => [
            'commentId',
        ],
        'urls' => [
            'self' => 'http://example.com/comments/{commentId}',
        ],
    ],
];

$mapper = new Mapper($mappings);
```

We'll see how the mapping works and outputs.



### 4.1. JSON
Given a PHP object, the JSON transformer will transform it to a valid JSON representation. It will preserve the data structure given by the properties of the classes and arrays used by the PHP Object.

Notice **all keys are normalized to under_score**. This differs from the `JsonTransformer` class provided by the `nilportugues/serializer` library.

**Code:**

```php
$transformer = new \NilPortugues\Api\Transformer\Json\JsonTransformer($mapper);
$serializer = new \NilPortugues\Serializer\Serializer($transformer);

$output = $serializer->serialize($post);
$response = new \NilPortugues\Api\Http\Message\Json\Response($output);

//PSR7 Response with headers and content.
header(
    sprintf(
        'HTTP/%s %s %s',
        $response->getProtocolVersion(),
        $response->getStatusCode(),
        $response->getReasonPhrase()
    )
);
foreach($response->getHeaders() as $header => $values) {
    header(sprintf("%s:%s\n", $header, implode(', ', $values)));
}

echo $response->getBody();
```

**Note:** `JsonTransformer` **optionally** requires the `Mapper`. If none is provided, class will be transformed to JSON but no other transformations will be applied.


**Output:**

```
HTTP/1.1 200 OK
Cache-Control: private, max-age=0, must-revalidate
Content-type: application/json; charset=utf-8
```

```json
{
    "post_id": 9,
    "headline": "Hello World",
    "body": "Your first post",
    "author": {
        "user_id": 1,
        "name": "Post Author"
    },
    "comments": [
        {
            "comment_id": 1000,
            "dates": {
                "created_at": "2015-07-27T20:59:45+02:00",
                "accepted_at": "2015-07-27T21:34:45+02:00"
            },
            "comment": "Have no fear, sers, your king is safe.",
            "user": {
                "user_id": 2,
                "name": "Barristan Selmy"
            }
        }
    ]
}
```


#### Response objects

The following PSR-7 Response objects providing the right headers and HTTP status codes are available:

- `NilPortugues\Api\Http\Message\Json\ErrorResponse($json)`
- `NilPortugues\Api\Http\Message\Json\ResourceCreatedResponse($json)`
- `NilPortugues\Api\Http\Message\Json\ResourceDeletedResponse($json)`
- `NilPortugues\Api\Http\Message\Json\ResourceNotFoundResponse($json)`
- `NilPortugues\Api\Http\Message\Json\ResourcePatchErrorResponse($json)`
- `NilPortugues\Api\Http\Message\Json\ResourcePostErrorResponse($json)`
- `NilPortugues\Api\Http\Message\Json\ResourceProcessingResponse($json)`
- `NilPortugues\Api\Http\Message\Json\ResourceUpdatedResponse($json)`
- `NilPortugues\Api\Http\Message\Json\Response($json)`
- `NilPortugues\Api\Http\Message\Json\UnsupportedActionResponse($json)`


### 4.2. JSend

JSend is a tiny and simple extension of JSON. Its implementation is really simple and follows the specification proposed by `http://labs.omniti.com/labs/jsend`.




```php
$transformer = new \NilPortugues\Api\Transformer\Json\JsonTransformer($mapper);
$serializer = new \NilPortugues\Serializer\Serializer($transformer);

$output = $serializer->serialize($post);

//Notice how here JSend response is used instead!!
$response = new \NilPortugues\Api\Http\Message\JSend\Response($output);


//PSR7 Response with headers and content.
header(
    sprintf(
        'HTTP/%s %s %s',
        $response->getProtocolVersion(),
        $response->getStatusCode(),
        $response->getReasonPhrase()
    )
);
foreach($response->getHeaders() as $header => $values) {
    header(sprintf("%s:%s\n", $header, implode(', ', $values)));
}

echo $response->getBody();
```

**Output:**

```
HTTP/1.1 200 OK
Cache-Control: private, max-age=0, must-revalidate
Content-type: application/json; charset=utf-8
```

```json
{
    "status": "success",
    "data": {
        "post_id": 9,
        "title": "Hello World",
        "content": "Your first post",
        "author": {
            "user_id": 1,
            "name": "Post Author"
        },
        "comments": [
            {
                "comment_id": 1000,
                "dates": {
                    "created_at": "2015-08-03T21:11:57+02:00",
                    "accepted_at": "2015-08-03T21:46:57+02:00"
                },
                "comment": "Have no fear, sers, your king is safe.",
                "user": {
                    "user_id": 2,
                    "name": "Barristan Selmy"
                }
            }
        ]
    }
}
```

#### Response objects

All you need is use the only 3 Response PSR-7 objects backing the proposed specification:

- `NilPortugues\Api\Http\Message\JSend\Response($json)`
- `NilPortugues\Api\Http\Message\JSend\FailResponse($content)`
- `NilPortugues\Api\Http\Message\JSend\ErrorResponse($message, $code = 500, $data = null)`


### 4.3. JSON API
Given a PHP Object, and a series of mappings, the JSON API transformer will represent the given data following the `http://jsonapi.org` specification.

**Code:**

```php
//Build the JsonApi Transformer and set additional fields.
$transformer = new \NilPortugues\Api\Transformer\Json\JsonApiTransformer($mapper);


//Output transformation
$serializer = new Serializer($transformer);
$serializer->setApiVersion('1.0');
$serializer->setSelfUrl('http://example.com/posts/9');
$serializer->setNextUrl('http://example.com/posts/10');
$serializer->addMeta('author',[['name' => 'Nil Portugués Calderó', 'email' => 'contact@nilportugues.com']]);

$output = $serializer->serialize($post);

//Notice the JsonApi Response object!!!
$response = new \NilPortugues\Api\Http\Message\JsonApi\Response($output);


//PSR7 Response with headers and content.
header(
    sprintf(
        'HTTP/%s %s %s',
        $response->getProtocolVersion(),
        $response->getStatusCode(),
        $response->getReasonPhrase()
    )
);
foreach($response->getHeaders() as $header => $values) {
    header(sprintf("%s:%s\n", $header, implode(', ', $values)));
}

echo $response->getBody();
```

**Output:**

```
HTTP/1.1 200 OK
Cache-Control: private, max-age=0, must-revalidate
Content-type: application/vnd.api+json
```

```json
{
    "data": {
        "type": "message",
        "id": "9",
        "attributes": {
            "headline": "Hello World",
            "body": "Your first post"
        },
        "links": {
            "self": "http://example.com/posts/9"
        },
        "relationships": {
            "author": {
                "links": {
                    "self": "http://example.com/posts/9/relationships/author",
                    "related": "http://example.com/posts/9/author"
                },
                "data": {
                    "type": "user",
                    "id": "1"
                }
            }
        }
    },
    "included": [
        {
            "type": "user",
            "id": "1",
            "attributes": {
                "name": "Post Author"
            },
            "links": {
                "self": "http://example.com/users/1"
            }
        },
        {
            "type": "user",
            "id": "2",
            "attributes": {
                "name": "Barristan Selmy"
            },
            "links": {
                "self": "http://example.com/users/2"
            }
        },
        {
            "type": "comment",
            "id": "1000",
            "attributes": {
                "dates": {
                    "created_at": "2015-07-27T19:33:44+02:00",
                    "accepted_at": "2015-07-27T20:08:44+02:00"
                },
                "comment": "Have no fear, sers, your king is safe."
            },
            "links": {
                "self": "http://example.com/comments/1000"
            }
        }
    ],
    "links": {
        "self": "http://example.com/posts/9",
        "next": "http://example.com/posts/10"
    },
    "meta": {
        "author": [
            {
                "name": "Nil Portugués Calderó",
                "email": "contact@nilportugues.com"
            }
        ]
    },
    "jsonapi": {
        "version": "1.0"
    }
}
```

#### Request objects

JsonApi comes with a helper Request class, `NilPortugues\Api\Http\Message\JsonApi\Request(ServerRequestInterface $request)`, implementing the PSR-7 Request Interface. Using this request object will provide you access to all the interactions expected in a JsonApi API:

##### JsonApi Query Parameters:

- &filter[resource]=field1,field2
- &include[resource]
- &include[resource.field1]
- &sort=field1,-field2
- &sort=-field1,field2
- &page[number]
- &page[limit]
- &page[cursor]
- &page[offset]
- &page[size]


##### NilPortugues\Api\Http\Message\JsonApi\Request Interface

Given the query parameters listed above, Request implements helper methods that parse and return data already prepared.

```php
namespace NilPortugues\Api\Http\Message\JsonApi;

final class Request
{
    public function __construct(ServerRequestInterface $request) { ... }
    public function getQueryParam($name, $default = null) { ... }
    public function getIncludedRelationships($baseRelationshipPath) { ... }
    public function getSortFields() { ... }
    public function getAttribute($name, $default = null) { ... }
    public function getSortDirection() { ... }
    public function getPageNumber() { ... }
    public function getPageLimit() { ... }
    public function getPageOffset() { ... }
    public function getPageSize() { ... }
    public function getPageCursor() { ... }
    public function getFilters() { ... }
}
```

#### Response objects

The following PSR-7 Response objects providing the right headers and HTTP status codes are available:

- `NilPortugues\Api\Http\Message\JsonApi\ErrorResponse($json)`
- `NilPortugues\Api\Http\Message\JsonApi\ResourceCreatedResponse($json)`
- `NilPortugues\Api\Http\Message\JsonApi\ResourceDeletedResponse($json)`
- `NilPortugues\Api\Http\Message\JsonApi\ResourceNotFoundResponse($json)`
- `NilPortugues\Api\Http\Message\JsonApi\ResourcePatchErrorResponse($json)`
- `NilPortugues\Api\Http\Message\JsonApi\ResourcePostErrorResponse($json)`
- `NilPortugues\Api\Http\Message\JsonApi\ResourceProcessingResponse($json)`
- `NilPortugues\Api\Http\Message\JsonApi\ResourceUpdatedResponse($json)`
- `NilPortugues\Api\Http\Message\JsonApi\Response($json)`
- `NilPortugues\Api\Http\Message\JsonApi\UnsupportedActionResponse($json)`


### 4.4. HAL+JSON
Given a PHP Object, and a series of mappings, the HAL+JSON API transformer will represent the given data following the `http://stateless.co/hal_specification.html` specification draft.


```php
//Build the JsonApi Transformer and set additional fields.
$transformer = new \NilPortugues\Api\Transformer\Json\JsonApiTransformer($mapper);


//Output transformation
$serializer = new Serializer($transformer);
$serializer->setApiVersion('1.0');
$serializer->setSelfUrl('http://example.com/posts/9');
$serializer->setNextUrl('http://example.com/posts/10');
$serializer->addMeta('author',[['name' => 'Nil Portugués Calderó', 'email' => 'contact@nilportugues.com']]);

$output = $serializer->serialize($post);

//Notice the HalJson Response object!!!
$response = new \NilPortugues\Api\Http\Message\HalJson\Response($output);


//PSR7 Response with headers and content.
header(
    sprintf(
        'HTTP/%s %s %s',
        $response->getProtocolVersion(),
        $response->getStatusCode(),
        $response->getReasonPhrase()
    )
);
foreach($response->getHeaders() as $header => $values) {
    header(sprintf("%s:%s\n", $header, implode(', ', $values)));
}

echo $response->getBody();
```

**Output:**

```
HTTP/1.1 200 OK
Cache-Control: private, max-age=0, must-revalidate
Content-type: application/hal+json
```

```json
```


#### Response objects

The following PSR-7 Response objects providing the right headers and HTTP status codes are available:

- `NilPortugues\Api\Http\Message\HalJson\ErrorResponse($json)`
- `NilPortugues\Api\Http\Message\HalJson\ResourceCreatedResponse($json)`
- `NilPortugues\Api\Http\Message\HalJson\ResourceDeletedResponse($json)`
- `NilPortugues\Api\Http\Message\HalJson\ResourceNotFoundResponse($json)`
- `NilPortugues\Api\Http\Message\HalJson\ResourcePatchErrorResponse($json)`
- `NilPortugues\Api\Http\Message\HalJson\ResourcePostErrorResponse($json)`
- `NilPortugues\Api\Http\Message\HalJson\ResourceProcessingResponse($json)`
- `NilPortugues\Api\Http\Message\HalJson\ResourceUpdatedResponse($json)`
- `NilPortugues\Api\Http\Message\HalJson\Response($json)`
- `NilPortugues\Api\Http\Message\HalJson\UnsupportedActionResponse($json)`


## 5. Quality Code
Testing has been done using PHPUnit and [Travis-CI](https://travis-ci.org). All code has been tested to be compatible from PHP 5.5 and above, plus [HHVM](http://hhvm.com/).

To run the test suite, you need [Composer](http://getcomposer.org):

```bash
    php composer.phar install
    php bin/phpunit
```


## 6. Questions?
Drop me an e-mail or get in touch with me using [![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/nilportugues/json-api?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)

## 7. Author
Nil Portugués Calderó

 - <contact@nilportugues.com>
 - [http://nilportugues.com](http://nilportugues.com)
