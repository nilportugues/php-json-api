<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/18/15
 * Time: 10:42 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Behaviour\Dummy\ComplexObject;

use NilPortugues\Tests\Api\JsonApi\Behaviour\Dummy\ComplexObject\ValueObject\CommentId;
use NilPortugues\Tests\Api\JsonApi\Behaviour\Dummy\ComplexObject\ValueObject\UserId;

class Comment
{
    /**
     * @var
     */
    private $commentId;
    /**
     * @var array
     */
    private $dates;
    /**
     * @var string
     */
    private $comment;

    /**
     * @var \DateTime
     */
    private $oneDate;

    /**
     * @var User[]
     */
    private $likes;
    /**
     * @param CommentId $id
     * @param           $comment
     * @param User      $user
     * @param array     $dates
     */
    public function __construct(CommentId $id, $comment, User $user, array $dates, \DateTime $d = null, $likes = [])
    {
        $this->commentId = $id;
        $this->comment = $comment;
        $this->user = $user;
        $this->dates = $dates;
        $this->oneDate = $d;
        $this->likes = $likes;
    }

    /**
     * @return mixed
     */
    public function getCommentId()
    {
        return $this->commentId;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return UserId
     */
    public function getUser()
    {
        return $this->user;
    }
}
