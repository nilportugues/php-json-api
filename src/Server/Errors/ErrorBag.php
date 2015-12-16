<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/20/15
 * Time: 7:22 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Server\Errors;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;

/**
 * Class ErrorBag.
 */
class ErrorBag implements JsonSerializable, ArrayAccess, Countable, IteratorAggregate
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @var
     */
    protected $httpCode;

    /**
     * @param array $errors
     */
    public function __construct(array $errors = [])
    {
        $this->errors = $errors;
    }

    /**
     * @param $httpCode
     */
    public function setHttpCode($httpCode)
    {
        $this->httpCode = $httpCode;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->errors);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->errors[] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->errors[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->errors);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        /** @var Error $error */
        foreach ($this->errors as $error) {
            $status = $error->getStatus();
            if (empty($status) && !empty($this->httpCode)) {
                $error->setStatus($this->httpCode);
            }
        }

        return ['errors' => array_values($this->errors)];
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->errors);
    }

    /**
     * @param $key
     *
     * @return null|mixed
     */
    protected function get($key)
    {
        return isset($this->errors[$key]) ? $this->errors[$key] : null;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array_values($this->errors);
    }
}
