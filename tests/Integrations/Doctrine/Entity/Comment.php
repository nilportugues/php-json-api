<?php

namespace NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity;

/**
 * Comment.
 */
class Comment
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var int
     */
    private $parent_id;

    /**
     * @var \NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity\Comment
     */
    private $parentComment;

    /**
     * @var \NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity\Post
     */
    private $post;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set comment.
     *
     * @param string $comment
     *
     * @return Comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set parentId.
     *
     * @param int $parentId
     *
     * @return Comment
     */
    public function setParentId($parentId)
    {
        $this->parent_id = $parentId;

        return $this;
    }

    /**
     * Get parentId.
     *
     * @return int
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * Set parentComment.
     *
     * @param \NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity\Comment $parentComment
     *
     * @return Comment
     */
    public function setParentComment(\NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity\Comment $parentComment = null)
    {
        $this->parentComment = $parentComment;

        return $this;
    }

    /**
     * Get parentComment.
     *
     * @return \NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity\Comment
     */
    public function getParentComment()
    {
        return $this->parentComment;
    }

    /**
     * Set post.
     *
     * @param \NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity\Post $post
     *
     * @return Comment
     */
    public function setPost(\NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post.
     *
     * @return \NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }
}
