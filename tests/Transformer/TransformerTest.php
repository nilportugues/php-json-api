<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/24/15
 * Time: 7:09 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Tests\Api\Transformer;

use NilPortugues\Api\Transformer\TransformerException;
use NilPortugues\Tests\Api\Dummy\DummyTransformer;

class TransformerTest extends \PHPUnit_Framework_TestCase
{
    public function testUnserializeThrowsException()
    {
        $this->setExpectedException(TransformerException::class);
        $transformer = new DummyTransformer([]);
        $transformer->unserialize(new \DateTime());
    }
}
