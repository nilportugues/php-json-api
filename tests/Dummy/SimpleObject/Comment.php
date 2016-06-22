<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/20/15
 * Time: 9:36 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Dummy\SimpleObject;

/**
 * Class Comment.
 */
class Comment
{
    /**
     * @var mixed
     */
    private $id;
    /**
     * @var \DateTime
     */
    private $createdAt;
    /**
     * @var
     */
    private $comment;

    /**
     * @param $id
     * @param $comment
     */
    public function __construct($id, $comment)
    {
        $this->id = $id;
        $this->comment = $comment;
        $this->createdAt = new \DateTime('2015-11-20 21:43:31');
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
