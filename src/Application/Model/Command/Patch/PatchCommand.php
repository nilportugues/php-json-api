<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 17/01/16
 * Time: 2:03.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Application\Command\Patch;

class PatchCommand
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $className;
    /**
     * @var array
     */
    private $data = [];

    /**
     * PatchResource constructor.
     *
     * @param string $id
     * @param string $className
     * @param array  $data
     */
    public function __construct($id, $className, array $data)
    {
        $this->id = $id;
        $this->className = $className;
        $this->data = $data;
    }

    /**
     * Returns value for `id`.
     *
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Returns value for `className`.
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
