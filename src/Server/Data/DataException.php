<?php
/**
* Author: Nil Portugués Calderó <contact@nilportugues.com>
* Date: 11/27/15
* Time: 10:00 PM.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace NilPortugues\Api\JsonApi\Server\Data;

use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;

/**
* Class DataException.
*/
class DataException extends \InvalidArgumentException
{
    /**
    * MessageBag errors.
    *
    * @var \NilPortugues\Api\JsonApi\Server\Errors\ErrorBag
    */
    protected $errors;

    /**
    * Create a new data exception instance.
    *
    * @param string                                                 $message
    * @param \NilPortugues\Api\JsonApi\Server\Errors\ErrorBag|array $errors
    * @param \Exception                                             $previous
    * @param array                                                  $headers
    * @param int                                                    $code
    *
    * @return void
    */
    public function __construct($message = null, $errors = null, $code = 0, Exception $previous = null)
    {
        if (is_null($errors)) {
            $this->errors = new ErrorBag;
        } else {
            $this->errors = is_array($errors) ? new ErrorBag($errors) : $errors;
        }
        parent::__construct($message, $code, $previous);
    }

    /**
    * Get the errors message bag.
    *
    * @return \Illuminate\Support\MessageBag
    */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
    * Determine if message bag has any errors.
    *
    * @return bool
    */
    public function hasErrors()
    {
        return (bool) $this->errors->count();
    }
}
