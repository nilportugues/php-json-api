<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/28/15
 * Time: 12:12 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Domain\Model\Exceptions;

use NilPortugues\Api\JsonApi\Domain\Model\Errors\ErrorBag;

/**
 * Class QueryException.
 */
class InputException extends \InvalidArgumentException
{
    /**
     * @var ErrorBag
     */
    private $errorBag;

    /**
     * QueryException constructor.
     *
     * @param ErrorBag $errorBag
     */
    public function __construct(ErrorBag $errorBag)
    {
        $this->errorBag = $errorBag;
        parent::__construct('Input error. See Error Bag.');
    }

    /**
     * Returns value for `errorBag`.
     *
     * @return ErrorBag
     */
    public function errorBag()
    {
        return $this->errorBag;
    }
}
