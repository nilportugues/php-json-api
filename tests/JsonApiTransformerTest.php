<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/18/15
 * Time: 11:27 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Tests\JsonApi;

use DateTime;
use NilPortugues\Api\JsonApi\JsonApiTransformer;
use NilPortugues\Api\Mapping\Mapper;
use NilPortugues\Api\Mapping\Mapping;
use NilPortugues\Api\Transformer\TransformerException;
use NilPortugues\Serializer\Serializer;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\Comment;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\Post;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\User;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\ValueObject\CommentId;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\ValueObject\PostId;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\ValueObject\UserId;
use NilPortugues\Tests\Api\JsonApi\Dummy\SimpleObject\Comment as SimpleComment;
use NilPortugues\Tests\Api\JsonApi\Dummy\SimpleObject\Post as SimplePost;

class JsonApiTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testItWillThrowExceptionIfNoMappingsAreProvided()
    {
        $mapper = new Mapper();
        $mapper->setClassMap([]);

        $this->setExpectedException(TransformerException::class);
        (new Serializer(new JsonApiTransformer($mapper)))->serialize(new \stdClass());
    }

    /**
     *
     */
    public function testItWillSerializeToJsonApiAComplexObject()
    {
        $mappings = [
            [
                'class' => Post::class,
                'aliased_properties' => [
                ],
                'hide_properties' => [

                ],
                'id_properties' => [
                    'postId',
                ],
                'urls' => [
                    // Mandatory
                    'self' => 'http://example.com/posts/{postId}',
                    // Optional
                    'comments' => 'http://example.com/posts/{postId}/comments',
                ],
                // (Optional) Used by JSONAPI
                'relationships' => [
                    'author' => [
                        'related' => ['name' => 'http://example.com/posts/{postId}/author'],
                        'self' => ['name' => 'http://example.com/posts/{postId}/relationships/author'],
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
                    'self' => ['name' => 'http://example.com/posts/{postId}'],
                    'relationships' => [
                        Comment::class => ['name' => 'http://example.com/posts/{postId}/relationships/comments'],
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
                    'self' => ['name' => 'http://example.com/users/{userId}'],
                    'friends' => ['name' => 'http://example.com/users/{userId}/friends'],
                    'comments' => ['name' => 'http://example.com/users/{userId}/comments'],
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
                    'self' => ['name' => 'http://example.com/users/{userId}'],
                    'friends' => ['name' => 'http://example.com/users/{userId}/friends'],
                    'comments' => ['name' => 'http://example.com/users/{userId}/comments'],
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
                    'self' => ['name' => 'http://example.com/comments/{commentId}'],
                ],
                'relationships' => [
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
                    'self' => ['name' => 'http://example.com/comments/{commentId}'],
                ],
            ],
        ];

        $mapper = new Mapper($mappings);

        $expected = <<<JSON
{
   "data":{
      "type":"post",
      "id":"9",
      "attributes":{
         "title":"Hello World",
         "content":"Your first post"
      },
      "links":{
         "self":{
            "href":"http://example.com/posts/9"
         },
         "comments":{
            "href":"http://example.com/posts/9/comments"
         }
      },
      "relationships":{
         "author":{
            "links":{
               "self":{
                  "href":"http://example.com/posts/9/relationships/author"
               },
               "related":{
                  "href":"http://example.com/posts/9/author"
               }
            },
            "data":{
               "type":"user",
               "id":"1"
            }
         },
         "comments":[
            {
               "data":{
                  "type":"comment",
                  "id":"1000"
               }
            }
         ]
      }
   },
   "included":[
      {
         "type":"user",
         "id":"1",
         "attributes":{
            "name":"Post Author"
         },
         "links":{
            "self":{
               "href":"http://example.com/users/1"
            },
            "friends":{
               "href":"http://example.com/users/1/friends"
            },
            "comments":{
               "href":"http://example.com/users/1/comments"
            }
         }
      },
      {
         "type":"user",
         "id":"2",
         "attributes":{
            "name":"Barristan Selmy"
         },
         "links":{
            "self":{
               "href":"http://example.com/users/2"
            },
            "friends":{
               "href":"http://example.com/users/2/friends"
            },
            "comments":{
               "href":"http://example.com/users/2/comments"
            }
         }
      },
      {
         "type":"comment",
         "id":"1000",
         "attributes":{
            "dates":{
               "created_at":"2015-07-18T12:13:00+00:00",
               "accepted_at":"2015-07-19T00:00:00+00:00"
            },
            "comment":"Have no fear, sers, your king is safe."
         },
         "relationships":{
            "user":{
               "data":{
                  "type":"user",
                  "id":"2"
               }
            }
         },
         "links":{
            "self":{
               "href":"http://example.com/comments/1000"
            }
         }
      }
   ],
   "links":{
      "self":{
         "href":"http://example.com/posts/9"
      },
      "first":{
         "href":"http://example.com/posts/1"
      },
      "next":{
         "href":"http://example.com/posts/10"
      },
      "comments":{
         "href":"http://example.com/posts/9/comments"
      }
   },
   "meta":{
      "author":{
         "name":"Nil Portugués Calderó",
         "email":"contact@nilportugues.com"
      },
      "is_devel":true
   },
   "jsonapi":{
      "version":"1.0"
   }
}
JSON;
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
                        'created_at' => (new DateTime('2015-07-18T12:13:00+00:00'))->format('c'),
                        'accepted_at' => (new DateTime('2015-07-19T00:00:00+00:00'))->format('c'),
                    ]
                ),
            ]
        );

        $transformer = new JsonApiTransformer($mapper);
        $transformer->setMeta(
            [
                'author' => [
                    'name' => 'Nil Portugués Calderó',
                    'email' => 'contact@nilportugues.com',
                ],
            ]
        );
        $transformer->addMeta('is_devel', true);
        $transformer->setSelfUrl('http://example.com/posts/9');
        $transformer->setFirstUrl('http://example.com/posts/1');
        $transformer->setNextUrl('http://example.com/posts/10');

        $this->assertEquals(
            \json_decode($expected, true),
            \json_decode((new Serializer($transformer))->serialize($post), true)
        );
    }

    /**
     *
     */
    public function testItWillSerializeToJsonApiASimpleObject()
    {
        $post = $this->createSimplePost();

        $postMapping = new Mapping(SimplePost::class, '/post/{postId}', ['postId']);

        $mapper = new Mapper();
        $mapper->setClassMap([$postMapping->getClassName() => $postMapping]);

        $jsonApiSerializer = new JsonApiTransformer($mapper);

        $expected = <<<JSON
{
    "data": {
        "type": "post",
        "id": "1",
        "attributes": {
            "title": "post title",
            "body": "post body",
            "author_id": 2,
            "comments": [
                {
                    "comment_id": 10,
                    "comment": "I am writing comment no. 1",
                    "user_id": "User 5",
                    "created_at": "2015-07-19T12:48:00+02:00"
                },
                {
                    "comment_id": 20,
                    "comment": "I am writing comment no. 2",
                    "user_id": "User 10",
                    "created_at": "2015-07-20T12:48:00+02:00"
                },
                {
                    "comment_id": 30,
                    "comment": "I am writing comment no. 3",
                    "user_id": "User 15",
                    "created_at": "2015-07-21T12:48:00+02:00"
                },
                {
                    "comment_id": 40,
                    "comment": "I am writing comment no. 4",
                    "user_id": "User 20",
                    "created_at": "2015-07-22T12:48:00+02:00"
                },
                {
                    "comment_id": 50,
                    "comment": "I am writing comment no. 5",
                    "user_id": "User 25",
                    "created_at": "2015-07-23T12:48:00+02:00"
                }
            ]
        },
        "links": {
            "self": { "href": "/post/1" }
        }
    },
    "jsonapi": {
        "version": "1.0"
    }
}
JSON;

        $this->assertEquals(
            \json_decode($expected, true),
            \json_decode((new Serializer($jsonApiSerializer))->serialize($post), true)
        );
    }

    /**
     *
     */
    public function testItWillRenamePropertiesFromClass()
    {
        $post = $this->createSimplePost();

        $postMapping = new Mapping(SimplePost::class, '/post/{postId}', ['postId']);
        $postMapping->setPropertyNameAliases(['title' => 'headline', 'body' => 'post', 'postId' => 'someId']);

        $mapper = new Mapper();
        $mapper->setClassMap([$postMapping->getClassName() => $postMapping]);

        $jsonApiSerializer = new JsonApiTransformer($mapper);

        $expected = <<<JSON
{
    "data": {
        "type": "post",
        "id": "1",
        "attributes": {
            "headline": "post title",
            "post": "post body",
            "author_id": 2,
            "comments": [
                {
                    "comment_id": 10,
                    "comment": "I am writing comment no. 1",
                    "user_id": "User 5",
                    "created_at": "2015-07-19T12:48:00+02:00"
                },
                {
                    "comment_id": 20,
                    "comment": "I am writing comment no. 2",
                    "user_id": "User 10",
                    "created_at": "2015-07-20T12:48:00+02:00"
                },
                {
                    "comment_id": 30,
                    "comment": "I am writing comment no. 3",
                    "user_id": "User 15",
                    "created_at": "2015-07-21T12:48:00+02:00"
                },
                {
                    "comment_id": 40,
                    "comment": "I am writing comment no. 4",
                    "user_id": "User 20",
                    "created_at": "2015-07-22T12:48:00+02:00"
                },
                {
                    "comment_id": 50,
                    "comment": "I am writing comment no. 5",
                    "user_id": "User 25",
                    "created_at": "2015-07-23T12:48:00+02:00"
                }
            ]
        },
        "links": {
            "self": { "href": "/post/1" }
        }
    },
    "jsonapi": {
        "version": "1.0"
    }
}
JSON;

        $this->assertEquals(
            \json_decode($expected, true),
            \json_decode((new Serializer($jsonApiSerializer))->serialize($post), true)
        );
    }

    /**
     *
     */
    public function testItWillHidePropertiesFromClass()
    {
        $post = $this->createSimplePost();

        $postMapping = new Mapping(SimplePost::class, '/post/{postId}', ['postId']);
        $postMapping->setHiddenProperties(['title', 'body']);

        $mapper = new Mapper();
        $mapper->setClassMap([$postMapping->getClassName() => $postMapping]);

        $jsonApiSerializer = new JsonApiTransformer($mapper);

        $expected = <<<JSON
{
    "data": {
        "type": "post",
        "id": "1",
        "attributes": {
            "author_id": 2,
            "comments": [
                {
                    "comment_id": 10,
                    "comment": "I am writing comment no. 1",
                    "user_id": "User 5",
                    "created_at": "2015-07-19T12:48:00+02:00"
                },
                {
                    "comment_id": 20,
                    "comment": "I am writing comment no. 2",
                    "user_id": "User 10",
                    "created_at": "2015-07-20T12:48:00+02:00"
                },
                {
                    "comment_id": 30,
                    "comment": "I am writing comment no. 3",
                    "user_id": "User 15",
                    "created_at": "2015-07-21T12:48:00+02:00"
                },
                {
                    "comment_id": 40,
                    "comment": "I am writing comment no. 4",
                    "user_id": "User 20",
                    "created_at": "2015-07-22T12:48:00+02:00"
                },
                {
                    "comment_id": 50,
                    "comment": "I am writing comment no. 5",
                    "user_id": "User 25",
                    "created_at": "2015-07-23T12:48:00+02:00"
                }
            ]
        },
        "links": {
            "self": { "href": "/post/1" }
        }
    },
    "jsonapi": {
        "version": "1.0"
    }
}
JSON;

        $this->assertEquals(
            \json_decode($expected, true),
            \json_decode((new Serializer($jsonApiSerializer))->serialize($post), true)
        );
    }

    public function testTypeValueIsChangedByClassAlias()
    {
        $post = $this->createSimplePost();

        $postMapping = new Mapping(SimplePost::class, '/post/{postId}', ['postId']);
        $postMapping->setClassAlias('Message');

        $mapper = new Mapper();
        $mapper->setClassMap([$postMapping->getClassName() => $postMapping]);

        $jsonApiSerializer = new JsonApiTransformer($mapper);

        $expected = <<<JSON
{
    "data": {
        "type": "message",
        "id": "1",
        "attributes": {
            "title": "post title",
            "body": "post body",
            "author_id": 2,
            "comments": [
                {
                    "comment_id": 10,
                    "comment": "I am writing comment no. 1",
                    "user_id": "User 5",
                    "created_at": "2015-07-19T12:48:00+02:00"
                },
                {
                    "comment_id": 20,
                    "comment": "I am writing comment no. 2",
                    "user_id": "User 10",
                    "created_at": "2015-07-20T12:48:00+02:00"
                },
                {
                    "comment_id": 30,
                    "comment": "I am writing comment no. 3",
                    "user_id": "User 15",
                    "created_at": "2015-07-21T12:48:00+02:00"
                },
                {
                    "comment_id": 40,
                    "comment": "I am writing comment no. 4",
                    "user_id": "User 20",
                    "created_at": "2015-07-22T12:48:00+02:00"
                },
                {
                    "comment_id": 50,
                    "comment": "I am writing comment no. 5",
                    "user_id": "User 25",
                    "created_at": "2015-07-23T12:48:00+02:00"
                }
            ]
        },
        "links": {
            "self": { "href": "/post/1"}
        }
    },
    "jsonapi": {
        "version": "1.0"
    }
}
JSON;

        $this->assertEquals(
            \json_decode($expected, true),
            \json_decode((new Serializer($jsonApiSerializer))->serialize($post), true)
        );
    }

    public function testItIfFilteringOutKeys()
    {
        $post = $this->createSimplePost();

        $postMapping = new Mapping(SimplePost::class, '/post/{postId}', ['postId']);
        $postMapping->setFilterKeys(['body']);

        $mapper = new Mapper();
        $mapper->setClassMap([$postMapping->getClassName() => $postMapping]);

        $jsonApiSerializer = new JsonApiTransformer($mapper);

        $expected = <<<JSON
{
    "data": {
        "type": "post",
        "id": "1",
        "attributes": {
            "body": "post body"
        },
        "links": {
            "self": { "href": "/post/1"}
        }
    },
    "jsonapi": {
        "version": "1.0"
    }
}
JSON;

        $this->assertEquals(
            \json_decode($expected, true),
            \json_decode((new Serializer($jsonApiSerializer))->serialize($post), true)
        );
    }

    /**
     * @return SimplePost
     */
    private function createSimplePost()
    {
        $post = new SimplePost(1, 'post title', 'post body', 2);

        for ($i = 1; $i <= 5; ++$i) {
            $userId = $i * 5;
            $createdAt = new \DateTime("2015/07/18 12:48:00 + $i days", new \DateTimeZone('Europe/Madrid'));
            $post->addComment($i * 10, "User {$userId}", "I am writing comment no. {$i}", $createdAt->format('c'));
        }

        return $post;
    }

    /**
     *
     */
    public function testItWillSerializeToJsonApiAnArrayOfObjects()
    {
        $postArray = [
            new SimplePost(1, 'post title 1', 'post body 1', 4),
            new SimplePost(2, 'post title 2', 'post body 2', 5),
        ];

        $postMapping = new Mapping(SimplePost::class, '/post/{postId}', ['postId']);
        $postMapping->setFilterKeys(['body', 'title']);

        $mapper = new Mapper();
        $mapper->setClassMap([$postMapping->getClassName() => $postMapping]);

        $jsonApiSerializer = new JsonApiTransformer($mapper);

        $expected = <<<JSON
{
   "data":[
      {
         "type":"post",
         "id":"1",
         "attributes":{
            "title":"post title 1",
            "body":"post body 1"
         },
         "links":{
            "self":{
               "href":"/post/1"
            }
         }
      },
      {
         "type":"post",
         "id":"2",
         "attributes":{
            "title":"post title 2",
            "body":"post body 2"
         },
         "links":{
            "self":{
               "href":"/post/2"
            }
         }
      }
   ],
   "jsonapi":{
      "version":"1.0"
   }
}
JSON;

        $this->assertEquals(
            \json_decode($expected, true),
            \json_decode((new Serializer($jsonApiSerializer))->serialize($postArray), true)
        );
    }

    /**
     *
     */
    public function testItWillBuildUrlUsingAliasOrTypeNameIfIdFieldNotPresentInUrl()
    {
        $post = $this->createSimplePost();

        $postMapping = new Mapping(SimplePost::class, '/post/{post}', ['postId']);
        $postMapping->setHiddenProperties(['title', 'body']);

        $mapper = new Mapper();
        $mapper->setClassMap([$postMapping->getClassName() => $postMapping]);

        $jsonApiSerializer = new JsonApiTransformer($mapper);

        $expected = <<<JSON
{
    "data": {
        "type": "post",
        "id": "1",
        "attributes": {
            "author_id": 2,
            "comments": [
                {
                    "comment_id": 10,
                    "comment": "I am writing comment no. 1",
                    "user_id": "User 5",
                    "created_at": "2015-07-19T12:48:00+02:00"
                },
                {
                    "comment_id": 20,
                    "comment": "I am writing comment no. 2",
                    "user_id": "User 10",
                    "created_at": "2015-07-20T12:48:00+02:00"
                },
                {
                    "comment_id": 30,
                    "comment": "I am writing comment no. 3",
                    "user_id": "User 15",
                    "created_at": "2015-07-21T12:48:00+02:00"
                },
                {
                    "comment_id": 40,
                    "comment": "I am writing comment no. 4",
                    "user_id": "User 20",
                    "created_at": "2015-07-22T12:48:00+02:00"
                },
                {
                    "comment_id": 50,
                    "comment": "I am writing comment no. 5",
                    "user_id": "User 25",
                    "created_at": "2015-07-23T12:48:00+02:00"
                }
            ]
        },
        "links": {
            "self": { "href": "/post/1" }
        }
    },
    "jsonapi": {
        "version": "1.0"
    }
}
JSON;

        $this->assertEquals(
            \json_decode($expected, true),
            \json_decode((new Serializer($jsonApiSerializer))->serialize($post), true)
        );
    }

    public function testItWillSerializeObjectsNotAddedInMappings()
    {
        $stdClass = new \stdClass();
        $stdClass->userName = 'Joe';
        $stdClass->commentBody = 'Hello World';

        $comment = new SimpleComment(1, $stdClass);
        $mapping = new Mapping(SimpleComment::class, '/comment/{id}', ['id']);

        $mapper = new Mapper();
        $mapper->setClassMap([$mapping->getClassName() => $mapping]);

        $jsonApiSerializer = new JsonApiTransformer($mapper);

        $expected = <<<JSON
{
   "data":{
      "type":"comment",
      "id":"1",
      "attributes":{
         "created_at":{
            "date":"2015-11-20 21:43:31.000000",
            "timezone_type":3,
            "timezone":"Europe/Madrid"
         },
         "comment":{
            "user_name":"Joe",
            "comment_body":"Hello World"
         }
      },
      "links":{
         "self":{
            "href":"/comment/1"
         }
      }
   },
   "jsonapi":{
      "version":"1.0"
   }
}
JSON;

        $this->assertEquals(
            \json_decode($expected, true),
            \json_decode((new Serializer($jsonApiSerializer))->serialize($comment), true)
        );
    }
}
