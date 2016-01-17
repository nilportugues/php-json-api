<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 16/01/16
 * Time: 23:22.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Application\Command\Delete;

class DeleteCommand
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
     * DeleteResource constructor.
     *
     * @param string $id
     * @param string $className
     */
    public function __construct($id, $className)
    {
        $this->id = $id;
        $this->className = $className;
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
}
