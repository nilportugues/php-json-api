 JSON API Transformer
=========================================

[![Build Status](https://travis-ci.org/nilportugues/jsonapi-transformer.svg)](https://travis-ci.org/nilportugues/jsonapi-transformer)
[![Coverage Status](https://coveralls.io/repos/nilportugues/json-api/badge.svg?branch=master&service=github?)](https://coveralls.io/github/nilportugues/json-api?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nilportugues/json-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nilportugues/json-api/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/e39e4c0e-a402-495b-a763-6e0482e2083d/mini.png)](https://insight.sensiolabs.com/projects/e39e4c0e-a402-495b-a763-6e0482e2083d) [![Latest Stable Version](https://poser.pugx.org/nilportugues/json-api/v/stable)](https://packagist.org/packages/nilportugues/json-api) [![Total Downloads](https://poser.pugx.org/nilportugues/json-api/downloads)](https://packagist.org/packages/nilportugues/json-api) [![License](https://poser.pugx.org/nilportugues/json-api/license)](https://packagist.org/packages/nilportugues/json-api) 




## Installation

Use [Composer](https://getcomposer.org) to install the package:

```json
$ composer require nilportugues/json-api
```

## Usage
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

And a Mapping array for all the involved classes:

```php
use NilPortugues\Api\Mapping\Mapper;

$mappings = [
    [
        'class' => Post::class,
        'alias' => 'Message',
        'aliased_properties' => [
            'author' => 'author',
            'title' => 'headline',
            'content' => 'body',
        ],
        'hide_properties' => [

        ],
        'id_properties' => [
            'postId',
        ],
        'urls' => [
            'self' => 'http://example.com/posts/{postId}',
            'comments' => 'http://example.com/posts/{postId}/comments'
        ],
        // (Optional)
        'relationships' => [
            'author' => [ //this key must match with the property or alias of the same name in Post class.
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
         ],
         'relationships' => [
              'comment' => [ //this key must match with the property or alias of the same name in PostId class.
                 'self' => 'http://example.com/posts/{postId}/relationships/comments',
              ],
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
            'friends' => 'http://example.com/users/{userId}/friends',
            'comments' => 'http://example.com/users/{userId}/comments',
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
            'friends' => 'http://example.com/users/{userId}/friends',
            'comments' => 'http://example.com/users/{userId}/comments',
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
            'post' => [ //this key must match with the property or alias of the same name in Commend class.
                'self' => 'http://example.com/posts/{postId}/relationships/comments',
            ]
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
        'relationships' => [
            'post' => [ //this key must match with the property or alias of the same name in CommendId class.
                'self' => 'http://example.com/posts/{postId}/relationships/comments',
            ]
        ],
    ],
];

$mapper = new Mapper($mappings);
```

Calling the transformer will output a **valid JSON API response** using the correct formatting:

```php
use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\JsonApiTransformer;
use NilPortugues\Api\JsonApi\Http\Message\Response;

$transformer = new JsonApiTransformer($mapper);

//Output transformation
$serializer = new JsonApiSerializer($transformer);
$serializer->getTransformer()->setSelfUrl('http://example.com/posts/9');
$serializer->getTransformer()->setNextUrl('http://example.com/posts/10');
$serializer->getTransformer()->addMeta('author',[['name' => 'Nil Portugués Calderó', 'email' => 'contact@nilportugues.com']]);

$output = $serializer->serialize($post);

//PSR7 Response with headers and content.
$response = new Response($output);

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
    "links": {
        "self": {
            "href": "http://example.com/posts/9"
        },
        "next": {
            "href": "http://example.com/posts/10"
        }
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

JSON API comes with its Request class, framework agnostic, implementing the PSR-7 Request Interface.

Using this request object will provide you access to all the interactions expected in a JSON API:

##### JSON API Query Parameters:

- **&fields[resource]=field1,field2** will only show the specified fields for a given resource.
- **&include[resource]**  show the relationship for a given resource even if it is filtered out by fields parameter.
- **&include[resource.field1]** show the relationship field even if it is filtered out by fields parameter.
- **&sort=field1,-field2** sort by field2 as DESC and field1 as ASC.
- **&sort=-field1,field2** sort by field1 as DESC and field2 as ASC.
- **&page[number]** will return the current page elements in a *page-based* pagination strategy.
- **&page[size]** will return the total amout of elements in a *page-based* pagination strategy.
- **&page[limit]** will return the limit in a *offset-based* pagination strategy.
- **&page[offset]** will return the offset value in a *offset-based* pagination strategy.
- **&page[cursor]** will return the cursor value  in a *cursor-based* pagination strategy.
- **&filter** will return data passed in the filter param.

##### NilPortugues\Api\JsonApi\Http\Message\Request

Given the query parameters listed above, Request implements helper methods that parse and return data already prepared.

```php
namespace NilPortugues\Api\JsonApi\Http\Message;

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
    public function getFields() { ... }
}
```

#### Response objects

The following PSR-7 Response objects providing the right headers and HTTP status codes are available:

- `NilPortugues\Api\JsonApi\Http\Message\Response($json)`
- `NilPortugues\Api\JsonApi\Http\Message\ResourceAccepted($json)`
- `NilPortugues\Api\JsonApi\Http\Message\ResourceCreated($json)`
- `NilPortugues\Api\JsonApi\Http\Message\ResourceDeleted($json)`
- `NilPortugues\Api\JsonApi\Http\Message\ResourceNotFound($json)`
- `NilPortugues\Api\JsonApi\Http\Message\ResourceConflicted($json)`
- `NilPortugues\Api\JsonApi\Http\Message\ResourceProcessing($json)`
- `NilPortugues\Api\JsonApi\Http\Message\ResourceUpdated($json)`
- `NilPortugues\Api\JsonApi\Http\Message\BadRequest($json)`
- `NilPortugues\Api\JsonApi\Http\Message\UnsupportedAction($json)`



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
