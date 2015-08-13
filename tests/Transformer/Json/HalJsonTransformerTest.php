<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/18/15
 * Time: 11:27 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Tests\Api\Transformer\Json;

use DateTime;
use NilPortugues\Api\Mapping\Mapper;
use NilPortugues\Api\Mapping\Mapping;
use NilPortugues\Api\Transformer\Json\HalJsonTransformer;
use NilPortugues\Api\Transformer\TransformerException;
use NilPortugues\Serializer\Serializer;
use NilPortugues\Tests\Api\Dummy\ComplexObject\Comment;
use NilPortugues\Tests\Api\Dummy\ComplexObject\Post;
use NilPortugues\Tests\Api\Dummy\ComplexObject\User;
use NilPortugues\Tests\Api\Dummy\ComplexObject\ValueObject\CommentId;
use NilPortugues\Tests\Api\Dummy\ComplexObject\ValueObject\PostId;
use NilPortugues\Tests\Api\Dummy\ComplexObject\ValueObject\UserId;
use NilPortugues\Tests\Api\Dummy\SimpleObject\Post as SimplePost;

class HalJsonTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testItWillSerializeToHalJsonAnArrayOfObjects()
    {
        $postArray = [
            new SimplePost(1, 'post title 1', 'post body 1', 4),
            new SimplePost(2, 'post title 2', 'post body 2', 5),
        ];

        $postMapping = new Mapping(SimplePost::class, '/post/{postId}', ['postId']);
        $postMapping->setFilterKeys(['body', 'title']);

        $mapper = new Mapper();
        $mapper->setClassMap([$postMapping->getClassName() => $postMapping]);

        $transformer = new HalJsonTransformer($mapper);

        $expected = <<<JSON
[
    {
        "post_id": 1,
        "title": "post title 1",
        "body": "post body 1",
        "author_id": 4
    },
    {
        "post_id": 2,
        "title": "post title 2",
        "body": "post body 2",
        "author_id": 5
    }
]
JSON;

        $this->assertEquals(
            json_decode($expected, true),
            json_decode((new Serializer($transformer))->serialize($postArray), true)
        );
    }

    /**
     *
     */
    public function testItWillThrowExceptionIfNoMappingsAreProvided()
    {
        $mapper = new Mapper();
        $mapper->setClassMap([]);

        $this->setExpectedException(TransformerException::class);
        (new Serializer(new HalJsonTransformer($mapper)))->serialize(new \stdClass());
    }

    /**
     *
     */
    public function testItWillSerializeToHalJsonAComplexObject()
    {
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
                    // Mandatory
                    'self' => 'http://example.com/posts/{postId}',
                    // Optional
                    'comments' => 'http://example.com/posts/{postId}/comments',
                ],
                // (Optional) Used by HAL+JSON
                'curies' => [
                    'name' => 'example',
                    'href' => 'http://example.com/docs/rels/{rel}',
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
                // (Optional) Used by HAL+JSON
                'curies' => [
                    'name' => 'example',
                    'href' => 'http://example.com/docs/rels/{rel}',
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
                // (Optional) Used by HAL+JSON
                'curies' => [
                    'name' => 'example',
                    'href' => 'http://example.com/docs/rels/{rel}',
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
                // (Optional) Used by HAL+JSON
                'curies' => [
                    'name' => 'example',
                    'href' => 'http://example.com/docs/rels/{rel}',
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
                // (Optional) Used by HAL+JSON
                'curies' => [
                    'name' => 'example',
                    'href' => 'http://example.com/docs/rels/{rel}',
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
                // (Optional) Used by HAL+JSON
                'curies' => [
                    'name' => 'example',
                    'href' => 'http://example.com/docs/rels/{rel}',
                ],
            ],
        ];

        $mapper = new Mapper($mappings);

        $expected = <<<JSON
{
   "post_id":9,
   "headline":"Hello World",
   "body":"Your first post",
   "_embedded":{
      "author":{
         "user_id":1,
         "name":"Post Author",
         "_links":{
            "self":{
               "href":"http://example.com/users/1"
            },
            "example:friends":{
               "href":"http://example.com/users/1/friends"
            },
            "example:comments":{
               "href":"http://example.com/users/1/comments"
            }
         }
      },
      "comments":[
         {
            "comment_id":1000,
            "dates":{
               "created_at":"2015-07-18T12:13:00+00:00",
               "accepted_at":"2015-07-19T00:00:00+00:00"
            },
            "comment":"Have no fear, sers, your king is safe.",
            "_embedded":{
               "user":{
                  "user_id":2,
                  "name":"Barristan Selmy",
                  "_links":{
                     "self":{
                        "href":"http://example.com/users/2"
                     },
                     "example:friends":{
                        "href":"http://example.com/users/2/friends"
                     },
                     "example:comments":{
                        "href":"http://example.com/users/2/comments"
                     }
                  }
               }
            },
            "_links":{
               "example:user":{
                  "href":"http://example.com/users/2"
               },
               "self":{
                  "href":"http://example.com/comments/1000"
               }
            }
         }
      ]
   },
   "_links":{
      "curies":[
         {
            "name":"example",
            "href":"http://example.com/docs/rels/{rel}",
            "templated":true
         }
      ],
      "self":{
         "href":"http://example.com/posts/9"
      },
      "first":{
         "href":"http://example.com/posts/1"
      },
      "next":{
         "href":"http://example.com/posts/10"
      },
      "example:author":{
         "href":"http://example.com/users/1"
      },
      "example:comments":{
         "href":"http://example.com/posts/9/comments"
      }
   },
   "_meta":{
      "author":{
         "name":"Nil Portugués Calderó",
         "email":"contact@nilportugues.com"
      },
      "is_devel":true
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

        $transformer = new HalJsonTransformer($mapper);
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
            json_decode($expected, true),
            json_decode((new Serializer($transformer))->serialize($post), true)
        );
    }

    /**
     *
     */
    public function testItWillSerializeToHalJsonASimpleObject()
    {
        $post = $this->createSimplePost();

        $postMapping = new Mapping(SimplePost::class, '/post/{postId}', ['postId']);

        $mapper = new Mapper();
        $mapper->setClassMap([$postMapping->getClassName() => $postMapping]);

        $transformer = new HalJsonTransformer($mapper);

        $expected = <<<JSON
{
    "post_id": 1,
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
}
JSON;

        $this->assertEquals(
            json_decode($expected, true),
            json_decode((new Serializer($transformer))->serialize($post), true)
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

        $transformer = new HalJsonTransformer($mapper);

        $expected = <<<JSON
{
    "some_id": 1,
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
}
JSON;

        $this->assertEquals(
            json_decode($expected, true),
            json_decode((new Serializer($transformer))->serialize($post), true)
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

        $transformer = new HalJsonTransformer($mapper);

        $expected = <<<JSON
{
    "post_id": 1,
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
}
JSON;

        $this->assertEquals(
            json_decode($expected, true),
            json_decode((new Serializer($transformer))->serialize($post), true)
        );
    }

    public function testTypeValueIsChangedByClassAlias()
    {
        $post = $this->createSimplePost();

        $postMapping = new Mapping(SimplePost::class, '/post/{postId}', ['postId']);
        $postMapping->setClassAlias('Message');

        $mapper = new Mapper();
        $mapper->setClassMap([$postMapping->getClassName() => $postMapping]);

        $transformer = new HalJsonTransformer($mapper);

        $expected = <<<JSON
{
    "post_id": 1,
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
}
JSON;

        $this->assertEquals(
            json_decode($expected, true),
            json_decode((new Serializer($transformer))->serialize($post), true)
        );
    }

    public function testItIfFilteringOutKeys()
    {
        $post = $this->createSimplePost();

        $postMapping = new Mapping(SimplePost::class, '/post/{postId}', ['postId']);
        $postMapping->setFilterKeys(['body']);

        $mapper = new Mapper();
        $mapper->setClassMap([$postMapping->getClassName() => $postMapping]);

        $transformer = new HalJsonTransformer($mapper);

        $expected = <<<JSON
{
    "post_id": 1,
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
}
JSON;

        $this->assertEquals(
            json_decode($expected, true),
            json_decode((new Serializer($transformer))->serialize($post), true)
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
}
