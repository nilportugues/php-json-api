<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/16/15
 * Time: 2:14 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi;

use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\Comment;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\Post;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\User;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\ValueObject\CommentId;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\ValueObject\PostId;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\ValueObject\UserId;

/**
 * Class HelperMapping.
 */
class HelperMapping
{
    public static function complex()
    {
        return $mappings = [
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
                        'comment' => ['name' => 'http://example.com/posts/{postId}/relationships/comments'],
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
    }
}
