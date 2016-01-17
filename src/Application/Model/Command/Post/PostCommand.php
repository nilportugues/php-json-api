<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 16/01/16
 * Time: 23:09.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Application\Command\Post;

class PostCommand
{
    /**
     * @var string
     */
    protected $className;
    /**
     * @var array
     */
    protected $data = [];

    /**
     * CreateResource constructor.
     *
     * @param string $className
     * @param array  $data
     */
    public function __construct($className, array $data)
    {
        $this->className = $className;
        $this->data = $data;
    }

    /**
     * Returns value for `classFQN`.
     *
     * @return string
     */
    public function className()
    {
        return $this->className;
    }

    /**
     * Returns value for `data`.
     *
     * @return array
     */
    public function data()
    {
        return $this->data;
    }
}
