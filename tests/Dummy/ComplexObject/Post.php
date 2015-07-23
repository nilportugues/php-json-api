<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/18/15
 * Time: 10:42 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\Dummy\ComplexObject;

use NilPortugues\Tests\Api\Dummy\ComplexObject\ValueObject\PostId;
use NilPortugues\Tests\Api\Dummy\ComplexObject\ValueObject\UserId;

class Post
{
    /**
     * @param PostId $id
     * @param $title
     * @param $content
     * @param User  $user
     * @param array $comments
     */
    public function __construct(PostId $id, $title, $content, User $user, array $comments)
    {
        $this->postId = $id;
        $this->title = $title;
        $this->content = $content;
        $this->author = $user;
        $this->comments = $comments;
    }

    /**
     * @return array
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return PostId
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return UserId
     */
    public function getUserId()
    {
        return $this->author;
    }
}
