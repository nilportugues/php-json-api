<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/24/15
 * Time: 7:08 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Tests\Api\Json\Dummy;

use NilPortugues\Api\Transformer\Transformer;

/**
 * Class DummyTransformer.
 */
class DummyTransformer extends Transformer
{
    /**
     * Represents the provided $value as a serialized value in string format.
     *
     * @param mixed $value
     *
     * @return string
     */
    public function serialize($value)
    {
        return 'dummy: '.$value;
    }
}
