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
use NilPortugues\Api\Mapping\Mapping;
use NilPortugues\Api\Transformer\Json\JsonApiTransformer;
use NilPortugues\Serializer\Serializer;
use NilPortugues\Tests\Api\Dummy\ComplexObject\Comment;
use NilPortugues\Tests\Api\Dummy\ComplexObject\Post;
use NilPortugues\Tests\Api\Dummy\ComplexObject\User;
use NilPortugues\Tests\Api\Dummy\ComplexObject\ValueObject\CommentId;
use NilPortugues\Tests\Api\Dummy\ComplexObject\ValueObject\PostId;
use NilPortugues\Tests\Api\Dummy\ComplexObject\ValueObject\UserId;
use NilPortugues\Tests\Api\Dummy\SimpleObject\Post as SimplePost;

class JsonApiTransformerTest extends \PHPUnit_Framework_TestCase
{
    public function testItWillSerializeToJsonApiAComplexObject()
    {
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

        $postMapping = new Mapping(Post::class, 'http://example.com/posts/{postId}', ['postId']);
        $postIdMapping = new Mapping(PostId::class, 'http://example.com/posts/{postId}', ['postId']);
        $userMapping = new Mapping(User::class, 'http://example.com/users/{userId}', ['userId']);
        $userIdMapping = new Mapping(UserId::class,  'http://example.com/users/{userId}', ['userId']);
        $commentMapping = new Mapping(Comment::class, 'http://example.com/comments/{commentId}', ['commentId']);
        $commentIdMapping = new Mapping(CommentId::class, 'http://example.com/comments/{commentId}', ['commentId']);

        $mappings = [
            $postMapping->getClassName() => $postMapping,
            $postIdMapping->getClassName() => $postIdMapping,
            $userMapping->getClassName() => $userMapping,
            $userIdMapping->getClassName() => $userIdMapping,
            $commentMapping->getClassName() => $commentMapping,
            $commentIdMapping->getClassName() => $commentIdMapping,
        ];

        $expected = <<<JSON
{
    "data": {
        "type": "post",
        "id": "9",
        "attributes": {
            "title": "Hello World",
            "content": "Your first post"
        },
        "links": {
            "self": "http://example.com/posts/9"
        },
        "relationships": {
            "author": {
                "links": {
                    "self": "http://example.com/users/1"
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
            },
            "relationships": [

            ]
        },
        {
            "type": "user",
            "id": "2",
            "attributes": {
                "name": "Barristan Selmy"
            },
            "links": {
                "self": "http://example.com/users/2"
            },
            "relationships": [

            ]
        },
        {
            "type": "comment",
            "id": "1000",
            "attributes": {
                "dates": {
                    "created_at": "2015-07-18T12:13:00+00:00",
                    "accepted_at": "2015-07-19T00:00:00+00:00"
                },
                "comment": "Have no fear, sers, your king is safe."
            },
            "links": {
                "self": "http://example.com/comments/1000"
            },
            "relationships": {
                "user": {
                    "links": {
                        "self": "http://example.com/users/2"
                    },
                    "data": {
                        "user": {
                            "type": "user",
                            "id": "2"
                        }
                    }
                }
            }
        }
    ],
    "links": {
        "self": "http://example.com/posts/1",
        "first": "http://example.com/posts/1",
        "next": "http://example.com/posts/2"
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
JSON;

        $transformer = new JsonApiTransformer($mappings);
        $transformer->setApiVersion('1.0');
        $transformer->setSelfUrl('http://example.com/posts/1');
        $transformer->setFirstUrl('http://example.com/posts/1');
        $transformer->setNextUrl('http://example.com/posts/2');
        $transformer->addMeta('author', [['name' => 'Nil Portugués Calderó', 'email' => 'contact@nilportugues.com']]);

        $this->assertEquals(
            json_decode($expected, true),
            json_decode((new Serializer($transformer))->serialize($post), true)
        );
    }

    public function testItWillSerializeToJsonApiASimpleObject()
    {
        $post = new SimplePost(1, 'post title', 'post body', 2);

        for ($i = 1;$i <= 5; ++$i) {
            $userId = $i * 5;
            $createdAt = new \DateTime("2015/07/18 12:48:00 + $i days", new \DateTimeZone('Europe/Madrid'));
            $post->addComment($i * 10, "User {$userId}", "I am writing comment no. {$i}", $createdAt->format('c'));
        }

        $postMapping = new Mapping(SimplePost::class, '/post/{postId}', ['postId']);
        $jsonApiSerializer = new JsonApiTransformer([$postMapping->getClassName() => $postMapping]);

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
            "self": "/post/1"
        },
        "relationships": [

        ]
    }
}
JSON;

        $this->assertEquals(
            json_decode($expected, true),
            json_decode((new Serializer($jsonApiSerializer))->serialize($post), true)
        );
    }

/*
    public function testItWillSerializeToJsonApiAnArrayOfObjects()
    {

    }*/
}
