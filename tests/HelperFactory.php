<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/16/15
 * Time: 2:21 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi;

use DateTime;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\Comment;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\Post;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\User;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\ValueObject\CommentId;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\ValueObject\PostId;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\ValueObject\UserId;
use NilPortugues\Tests\Api\JsonApi\Dummy\SimpleObject\Post as SimplePost;

/**
 * Class HelperFactory.
 */
class HelperFactory
{
    /**
     * @return Post
     */
    public static function complexPost()
    {
        return new Post(
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
                    ],
                    new DateTime('2015-07-18T12:13:00+00:00')
                ),
            ]
        );
    }

    /**
     * @return SimplePost
     */
    public static function simplePost()
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
