 JSON API Transformer & Server Helpers
=========================================

[![Build Status](https://travis-ci.org/nilportugues/jsonapi-transformer.svg)](https://travis-ci.org/nilportugues/jsonapi-transformer)
[![Coverage Status](https://coveralls.io/repos/nilportugues/json-api/badge.svg?branch=master&service=github?)](https://coveralls.io/github/nilportugues/json-api?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nilportugues/json-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nilportugues/json-api/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/e39e4c0e-a402-495b-a763-6e0482e2083d/mini.png)](https://insight.sensiolabs.com/projects/e39e4c0e-a402-495b-a763-6e0482e2083d) [![Latest Stable Version](https://poser.pugx.org/nilportugues/json-api/v/stable)](https://packagist.org/packages/nilportugues/json-api) [![Total Downloads](https://poser.pugx.org/nilportugues/json-api/downloads)](https://packagist.org/packages/nilportugues/json-api) [![License](https://poser.pugx.org/nilportugues/json-api/license)](https://packagist.org/packages/nilportugues/json-api) 


 - [Installation](#installation)
 - [Transfomer Classes](#transfomer-classes)
 - [Server Classes](#server-classes)
    - [JSON API Request object](#json-api-request-object)
    - [Request Object](#request-object)
    - [JSON API Response objects](#json-api-response-objects)
    - [Action Objects](#action-objects)
    

# Installation

Use [Composer](https://getcomposer.org) to install the package:

```json
$ composer require nilportugues/json-api
```


# Transfomer Classes

Given a PHP Object, and a series of mappings, the **JSON API Transformer** will represent the given data following the `http://jsonapi.org` specification.

For instance, given the following piece of code, defining a Blog Post and some comments:

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

And a Mapping series of classes implementing `JsonApiMapping` interface.

```php
<?php
namespace AcmeProject\Infrastructure\Api\Mappings;

use NilPortugues\Api\Mappings\JsonApiMapping;

class PostMapping  implements JsonApiMapping
{
    /**
     * {@inhertidoc}
     */
    public function getClass() 
    {
        return \Post::class;
    }
    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'Message';
    }
    /**
     * {@inheritdoc}
     */
    public function getAliasedProperties() {
        return [
            'author' => 'author',
            'title' => 'headline',
            'content' => 'body',
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function getHideProperties(){
        return [];
    }
    /**
     * {@inheritdoc}
     */
    public function getIdProperties()
        return [ 
            'postId',
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function getUrls()
    {
        return [
            'self' => 'http://example.com/posts/{postId}',
            'comments' => 'http://example.com/posts/{postId}/comments'
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function getRelationships()
    {
        return [
            'author' => [ //this key must match with the property or alias of the same name in Post class.
                'related' => 'http://example.com/posts/{postId}/author',
                'self' => 'http://example.com/posts/{postId}/relationships/author',
            ]
        ];
    }
}
```

```php
<?php
namespace AcmeProject\Infrastructure\Api\Mappings;

use NilPortugues\Api\Mappings\JsonApiMapping;

class PostIdMapping implements JsonApiMapping
{
    /**
     * {@inhertidoc}
     */
    public function getClass() 
    {
        return \PostId::class;
    }
    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return '';
    }
    /**
     * {@inheritdoc}
     */
    public function getAliasedProperties() {
        return [],    
    }
    /**
     * {@inheritdoc}
     */    
    public function getHideProperties(){
        return [];
    }
    /**
     * {@inheritdoc}
     */
    public function getIdProperties()
        return [
            'postId',
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function getUrls()
    {
        return [
            'self' => 'http://example.com/posts/{postId}',
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function getRelationships()
    {
        return [
            'comment' => [ //this key must match with the property or alias of the same name in PostId class.
                'self' => 'http://example.com/posts/{postId}/relationships/comments',
                ],
            ],
        ];
    }
}
```

```php
<?php
namespace AcmeProject\Infrastructure\Api\Mappings;

use NilPortugues\Api\Mappings\JsonApiMapping;

class UserMapping implements JsonApiMapping
{
    /**
     * {@inhertidoc}
     */
    public function getClass() 
    {
        return \User::class;
    }
    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return '';
    }
    /**
     * {@inheritdoc}
     */
    public function getAliasedProperties() {
        return [];
    }
    /**
     * {@inheritdoc}
     */
    public function getHideProperties(){
        return [];
    }
    /**
     * {@inheritdoc}
     */
    public function getIdProperties()
        return [
            'userId',
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function getUrls()
    {
        return [
            'self' => 'http://example.com/users/{userId}',
            'friends' => 'http://example.com/users/{userId}/friends',
            'comments' => 'http://example.com/users/{userId}/comments',
        ];
    }
}
```

```php
<?php
namespace AcmeProject\Infrastructure\Api\Mappings;

use NilPortugues\Api\Mappings\JsonApiMapping;

class UserIdMapping implements JsonApiMapping
{
    /**
     * {@inhertidoc}
     */
    public function getClass() 
    {
        return \UserId::class;
    }
    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return '';
    }
    /**
     * {@inheritdoc}
     */
    public function getAliasedProperties() {
        return [];
    }
    /**
     * {@inheritdoc}
     */
    public function getHideProperties(){
        return [];
    }
    /**
     * {@inheritdoc}
     */
    public function getIdProperties()
        return [ 'userId',];
    }
    /**
     * {@inheritdoc}
     */
    public function getUrls()
    {
        return [
            'self' => 'http://example.com/users/{userId}',
            'friends' => 'http://example.com/users/{userId}/friends',
            'comments' => 'http://example.com/users/{userId}/comments',
        ];
    }
}
```


```php
<?php
namespace AcmeProject\Infrastructure\Api\Mappings;

use NilPortugues\Api\Mappings\JsonApiMapping;

class CommendMapping implements JsonApiMapping
{
    /**
     * {@inhertidoc}
     */
    public function getClass() 
    {
        return \Comment::class;
    }
    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return '';
    }
    /**
     * {@inheritdoc}
     */
    public function getAliasedProperties() {
        return [];
    }
    /**
     * {@inheritdoc}
     */
    public function getHideProperties(){
        return [];
    }
    /**
     * {@inheritdoc}
     */
    public function getIdProperties()
        return [ 'commentId',];
    }
    /**
     * {@inheritdoc}
     */
    public function getUrls()
    {
        return [
            'self' => 'http://example.com/comments/{commentId}',
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function getRelationships()
    {
        return [
            'post' => [ //this key must match with the property or alias of the same name in Commend class.
                'self' => 'http://example.com/posts/{postId}/relationships/comments',
            ]
        ];
    }
}
```

```php
<?php
namespace AcmeProject\Infrastructure\Api\Mappings;

use NilPortugues\Api\Mappings\JsonApiMapping;

class CommentId implements JsonApiMapping
{
    /**
     * {@inhertidoc}
     */
    public function getClass() 
    {
        return \CommentId::class;
    }
    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return '';
    }
    /**
     * {@inheritdoc}
     */
    public function getAliasedProperties() {
        return [];
    }
    /**
     * {@inheritdoc}
     */
    public function getHideProperties(){
        return [];
    }
    /**
     * {@inheritdoc}
     */
    public function getIdProperties() {
        return [ 'commentId', ];
    }
    /**
     * {@inheritdoc}
     */
    public function getUrls()
    {
        return [
            'self' => 'http://example.com/comments/{commentId}',
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function getRelationships()
    {
        return [
            'post' => [ //this key must match with the property or alias of the same name in CommendId class.
                'self' => 'http://example.com/posts/{postId}/relationships/comments',
            ]
        ];
    }
}
```


Calling the transformer will output a **valid JSON API response** using the correct formatting:

```php
<?php

use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\JsonApiTransformer;
use NilPortugues\Api\JsonApi\Http\Message\Response;
use NilPortugues\Api\Mapping\Mapper;

$classConfig = [
    \AcmeProject\Infrastructure\Api\Mappings\PostMapping::class,
    \AcmeProject\Infrastructure\Api\Mappings\PostIdMapping::class,
    \AcmeProject\Infrastructure\Api\Mappings\UserMapping::class,
    \AcmeProject\Infrastructure\Api\Mappings\UserIdMapping::class,
    \AcmeProject\Infrastructure\Api\Mappings\CommendMapping::class,
    \AcmeProject\Infrastructure\Api\Mappings\CommentId::class,
];

$mapper = new Mapper($mappings);

$transformer = new JsonApiTransformer($mapper);
$serializer = new JsonApiSerializer($transformer);

echo $serializer->serialize($post);
```

**Output (formatted):**

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
            "self": {
                "href": "http://example.com/posts/9"
            },
            "comments": {
                "href": "http://example.com/posts/9/comments"
            }
        },
        "relationships": {
            "author": {
                "links": {
                    "self": {
                        "href": "http://example.com/posts/9/relationships/author"
                    },
                    "related": {
                        "href": "http://example.com/posts/9/author"
                    }
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
                "self": {
                    "href": "http://example.com/users/1"
                },
                "friends": {
                    "href": "http://example.com/users/1/friends"
                },
                "comments": {
                    "href": "http://example.com/users/1/comments"
                }
            }
        },
        {
            "type": "user",
            "id": "2",
            "attributes": {
                "name": "Barristan Selmy"
            },
            "links": {
                "self": {
                    "href": "http://example.com/users/2"
                },
                "friends": {
                    "href": "http://example.com/users/2/friends"
                },
                "comments": {
                    "href": "http://example.com/users/2/comments"
                }
            }
        },
        {
            "type": "comment",
            "id": "1000",
            "attributes": {
                "dates": {
                    "created_at": "2015-08-13T21:11:07+02:00",
                    "accepted_at": "2015-08-13T21:46:07+02:00"
                },
                "comment": "Have no fear, sers, your king is safe."
            },
            "relationships": {
                "user": {
                    "data": {
                        "type": "user",
                        "id": "2"
                    }
                }
            },
            "links": {
                "self": {
                    "href": "http://example.com/comments/1000"
                }
            }
        }
    ],
    "jsonapi": {
        "version": "1.0"
    }
}
```

---

# Server Classes

## JSON API Request object

JSON API comes with its Request class, framework agnostic, implementing the PSR-7 Request Interface.

Using this request object will provide you access to all the interactions expected in a JSON API:

#### Defined Query Parameters:

- **&fields[resource]=field1,field2** will only show the specified fields for a given resource.
- **&include=resource** show the relationship for a given resource.
- **&include=resource.resource2** show the relationship field for those depending on resource2.
- **&sort=field1,-field2** sort by field2 as DESC and field1 as ASC.
- **&sort=-field1,field2** sort by field1 as DESC and field2 as ASC.
- **&page[number]** will return the current page elements in a *page-based* pagination strategy.
- **&page[size]** will return the total amout of elements in a *page-based* pagination strategy.
- **&page[limit]** will return the limit in a *offset-based* pagination strategy.
- **&page[offset]** will return the offset value in a *offset-based* pagination strategy.
- **&page[cursor]** will return the cursor value  in a *cursor-based* pagination strategy.
- **&filter** will return data passed in the filter param.

## Request Object

Given the query parameters listed above, Request implements helper methods that parse and return data already prepared.

```php
namespace \NilPortugues\Api\JsonApi\Http\Request;

class Request
{
  public function __construct(ServerRequestInterface $request = null) { ... }
  public function getQueryParam($name, $default = null) { ... }
  public function getIncludedRelationships() { ... }
  public function getSortFields() { ... }
  public function getSortDirection() { ... }
  public function getPageNumber($default = 1) { ... }
  public function getPageLimit() { ... }
  public function getPageOffset() { ... }
  public function getPageSize($default = 10) { ... }
  public function getPageCursor() { ... }
  public function getFilters() { ... }
  public function getFields() { ... }
}
```

## JSON API Response objects

Because the JSON API specification lists a set of behaviours, specific Response objects are provided for successful and error cases.

**Success**

- `NilPortugues\Api\JsonApi\Http\Response\Response`
- `NilPortugues\Api\JsonApi\Http\Response\ResourceUpdated`
- `NilPortugues\Api\JsonApi\Http\Response\ResourceAccepted`
- `NilPortugues\Api\JsonApi\Http\Response\ResourceCreated`
- `NilPortugues\Api\JsonApi\Http\Response\ResourceDeleted`
- `NilPortugues\Api\JsonApi\Http\Response\ResourceProcessing`

**Error**

- `NilPortugues\Api\JsonApi\Http\Response\BadRequest`
- `NilPortugues\Api\JsonApi\Http\Response\ResourceConflicted`
- `NilPortugues\Api\JsonApi\Http\Response\ResourceNotFound`
- `NilPortugues\Api\JsonApi\Http\Response\TooManyRequests`
- `NilPortugues\Api\JsonApi\Http\Response\UnprocessableEntity`
- `NilPortugues\Api\JsonApi\Http\Response\UnsupportedAction`


## Action Objects

Having Request and Response objects and Transformers, it just makes sense to have a set of classes that tie them all together into something more powerful: **Actions**.

Provided actions are: 

- `NilPortugues\Api\JsonApi\Server\Actions\CreateResource`
- `NilPortugues\Api\JsonApi\Server\Actions\DeleteResource`
- `NilPortugues\Api\JsonApi\Server\Actions\GetResource`
- `NilPortugues\Api\JsonApi\Server\Actions\ListResource`
- `NilPortugues\Api\JsonApi\Server\Actions\PatchResource`
- `NilPortugues\Api\JsonApi\Server\Actions\PutResource`

All actions share a `get` method to run the Resource. 

These `get` methods will expect in all cases one or more `callables`. This has been done to avoid coupling with any library or interface and being able to extend it.

---

## Quality

To run the PHPUnit tests at the command line, go to the tests directory and issue phpunit.

This library attempts to comply with [PSR-1](http://www.php-fig.org/psr/psr-1/), [PSR-2](http://www.php-fig.org/psr/psr-2/), [PSR-4](http://www.php-fig.org/psr/psr-4/) and [PSR-7](http://www.php-fig.org/psr/psr-7/).

If you notice compliance oversights, please send a patch via [Pull Request](https://github.com/nilportugues/jsonapi-transformer/pulls).



## Contribute

Contributions to the package are always welcome!

* Report any bugs or issues you find on the [issue tracker](https://github.com/nilportugues/jsonapi-transformer/issues/new).
* You can grab the source code at the package's [Git repository](https://github.com/nilportugues/jsonapi-transformer).



## Support

Get in touch with me using one of the following means:

 - Emailing me at <contact@nilportugues.com>
 - Opening an [Issue](https://github.com/nilportugues/jsonapi-transformer/issues/new)
 - Using Gitter: [![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/nilportugues/jsonapi-transformer?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)



## Authors

* [Nil Portugués Calderó](http://nilportugues.com)
* [The Community Contributors](https://github.com/nilportugues/jsonapi-transformer/graphs/contributors)


## License
The code base is licensed under the [MIT license](LICENSE).
