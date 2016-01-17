<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 16/01/16
 * Time: 23:32.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Application\Query\GetOne;

class GetOneQuery
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
     * GetResource constructor.
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
     * Returns value for `className`.
     *
     * @return string
     */
    public function className()
    {
        return $this->className;
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
}
