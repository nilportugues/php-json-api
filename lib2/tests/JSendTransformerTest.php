<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/20/15
 * Time: 9:04 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Tests\Api\Transformer\Json;

use DateTime;
use NilPortugues\Api\JSend\JSendTransformer;
use NilPortugues\Api\Mapping\Mapper;
use NilPortugues\Serializer\Serializer;
use NilPortugues\Tests\Api\JSend\Dummy\ComplexObject\Comment;
use NilPortugues\Tests\Api\JSend\Dummy\ComplexObject\Post;
use NilPortugues\Tests\Api\JSend\Dummy\ComplexObject\User;
use NilPortugues\Tests\Api\JSend\Dummy\ComplexObject\ValueObject\CommentId;
use NilPortugues\Tests\Api\JSend\Dummy\ComplexObject\ValueObject\PostId;
use NilPortugues\Tests\Api\JSend\Dummy\ComplexObject\ValueObject\UserId;

class JSendTransformerTest extends \PHPUnit_Framework_TestCase
{
    public function testItWillSerializeToJsonApiAComplexObject()
    {
        $post = $this->getPostObject();

        $expected = <<<JSON
{
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
                    "created_at": "2015-07-18T12:13:00+02:00",
                    "accepted_at": "2015-07-19T00:00:00+02:00"
                },
                "comment": "Have no fear, sers, your king is safe.",
                "user": {
                    "user_id": 2,
                    "name": "Barristan Selmy"
                }
            }
        ]
    },
    "meta" : {
        "author": {
            "name": "Nil Portugués Calderó",
            "email": "contact@nilportugues.com"
        }
    }
}
JSON;

        $transformer = new JSendTransformer(new Mapper());
        $transformer->setMeta(
            [
                'author' => [
                    'name' => 'Nil Portugués Calderó',
                    'email' => 'contact@nilportugues.com',
                ],
            ]
        );

        $this->assertEquals(
            json_decode($expected, true),
            json_decode((new Serializer($transformer))->serialize($post), true)
        );
    }

    /**
     * @return Post
     */
    private function getPostObject()
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
                        'created_at' => (new DateTime(
                                '2015-07-18 12:13',
                                new \DateTimeZone('Europe/Madrid')
                            ))->format('c'),
                        'accepted_at' => (new DateTime(
                                '2015-07-19 00:00',
                                new \DateTimeZone('Europe/Madrid')
                            ))->format('c'),
                    ]
                ),
            ]
        );

        return $post;
    }

    /**
     *
     */
    public function testItWillRenamePropertiesAndHideFromClass()
    {
        $mappings = [
            [
                'class' => Post::class,
                'alias' => 'Message',
                'aliased_properties' => [
                    'title' => 'headline',
                    'content' => 'body',
                ],
                'hide_properties' => [
                    'comments',
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
            ],
        ];

        $expected = <<<JSON
{
    "data": {
        "post_id": 9,
        "headline": "Hello World",
        "body": "Your first post",
        "author": {
            "user_id": 1,
            "name": "Post Author"
        }
    },
    "links": {
        "comments": {"href": "http://example.com/posts/9/comments" }
    }
}
JSON;

        $this->assertEquals(
            json_decode($expected, true),
            json_decode(
                (new Serializer(new JSendTransformer(new Mapper($mappings))))->serialize($this->getPostObject()),
                true
            )
        );
    }
}
