 JSON API Transformers 
=========================================

[![Build Status](https://travis-ci.org/nilportugues/json-api.svg)](https://travis-ci.org/nilportugues/json-api) [![Coverage Status](https://coveralls.io/repos/nilportugues/json-api/badge.svg?branch=master&service=github)](https://coveralls.io/github/nilportugues/json-api?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nilportugues/json-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nilportugues/json-api/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/e39e4c0e-a402-495b-a763-6e0482e2083d/mini.png)](https://insight.sensiolabs.com/projects/e39e4c0e-a402-495b-a763-6e0482e2083d) [![Latest Stable Version](https://poser.pugx.org/nilportugues/json-api/v/stable)](https://packagist.org/packages/nilportugues/json-api) [![Total Downloads](https://poser.pugx.org/nilportugues/json-api/downloads)](https://packagist.org/packages/nilportugues/json-api) [![License](https://poser.pugx.org/nilportugues/json-api/license)](https://packagist.org/packages/nilportugues/json-api) 

Serializer transformers outputting valid API responses in JSON, JSON API and HAL+JSON API formats.

## Purpose

Web APIs are quick becoming the centerpiece of today entreprises and business, big or small, and allow us **to connect anything and everything**. By exposing data and application functionality to external applications **any organization can remake its business into an extensible platform**. 

API are a mandated requirement for today's modern enterprise, **enabling interactions with customers** over new mobility and social channels and evolving ways to **reach new customers through partner and third party applications**.

The provided **JSON API package** will allow you to **accomplish this goal in no time**.

## Features

- Transform to JSON, JSONAPI and HAL+JSON format using mappings. 
- Supports nested classes, no matter its complexity.
- Allows renaming of classes and properties.
- Allows hiding properties.
- Completely decoupled from any framework.
- Fully tested and high quality code.
- Actively supported and maintained.

## Installation
The recommended way to install the  JSON API Transformers is through [Composer](http://getcomposer.org). Run the following command to install it:

```sh
php composer.phar require nilportugues/json-api
```

## Usage
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
            'related' => 'http://example.com/posts/{postId}/author',
            'relationships' => [
                'self' => 'http://example.com/posts/{postId}/relationships/author',
            ],
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
            'relationships' => [
                Comment::class => 'http://example.com/posts/{postId}/relationships/comments',
            ],
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
            'relationships' => [
                Post::class => 'http://example.com/posts/{postId}/relationships/comments',
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



### JSON 
Given a PHP object, the JSON transformer will transform it to a valid JSON representation. It will preserve the data structure given by the properties of the classes and arrays used by the PHP Object.

Notice **all keys are normalized to under_score**. This differs from the `JsonTransformer` class provided by the `nilportugues/serializer` library.

**Code:**

```php
use NilPortugues\Serializer\Serializer;
use NilPortugues\Api\Transformer\Json\JsonTransformer;

$transformer = new JsonTransformer();
$serializer = new Serializer($transformer);

echo $serializer->serialize($post);
```

**Output:**


```json
{
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
                "created_at": "2015-07-18T13:34:55+02:00",
                "accepted_at": "2015-07-18T14:09:55+02:00"
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


### JSON API
Given a PHP Object, and a series of mappings, the JSON API transformer will represent the given data following the `http://jsonapi.org` specification.

**Code:**

```php

//Build the JsonApi Transformer and set additional fields.
$transformer = new JsonApiTransformer($mappings);
$transformer->setApiVersion('1.0');
$transformer->setSelfUrl('http://example.com/posts/9');
$transformer->setNextUrl('http://example.com/posts/10');
$transformer->addMeta(
   'author', 
   [
      ['name' => 'Nil Portugués Calderó', 'email' => 'contact@nilportugues.com']
   ]
);

//Output transformation
$serializer = new Serializer($transformer);

echo $serializer->serialize($post);
```

**Output:**

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

### (WIP) HAL+JSON
Given a PHP Object, and a series of mappings, the HAL+JSON API transformer will represent the given data following the `http://stateless.co/hal_specification.html` specification.


## Quality Code
Testing has been done using PHPUnit and [Travis-CI](https://travis-ci.org). All code has been tested to be compatible from PHP 5.5 and above, plus [HHVM](http://hhvm.com/).

To run the test suite, you need [Composer](http://getcomposer.org):

```bash
    php composer.phar install
    php bin/phpunit
```


## Questions?
Drop me an e-mail or get in touch with me using [![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/nilportugues/json-api?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)

## Author
Nil Portugués Calderó

 - <contact@nilportugues.com>
 - [http://nilportugues.com](http://nilportugues.com)
