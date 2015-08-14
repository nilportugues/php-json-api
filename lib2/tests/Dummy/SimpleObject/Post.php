<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/18/15
 * Time: 11:21 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Tests\Api\JSend\Dummy\SimpleObject;

class Post
{
    /**
     * @var
     */
    private $postId;
    /**
     * @var
     */
    private $title;
    /**
     * @var
     */
    private $body;
    /**
     * @var
     */
    private $authorId;
    /**
     * @var array
     */
    private $comments = [];

    /**
     * @param $postId
     * @param $title
     * @param $body
     * @param $authorId
     */
    public function __construct($postId, $title, $body, $authorId)
    {
        $this->postId = $postId;
        $this->title = $title;
        $this->body = $body;
        $this->authorId = $authorId;
    }

    /**
     * @param $commentId
     * @param $user
     * @param $comment
     * @param $created_at
     */
    public function addComment($commentId, $user, $comment, $created_at)
    {
        $this->comments[] = [
            'comment_id' => $commentId,
            'comment' => $comment,
            'user_id' => $user,
            'created_at' => $created_at,
        ];
    }

    /**
     * @param mixed $authorId
     *
     * @return $this
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * @param mixed $body
     *
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $commentId
     *
     * @return $this
     */
    public function setPostId($commentId)
    {
        $this->postId = $commentId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * @param mixed $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }
}
