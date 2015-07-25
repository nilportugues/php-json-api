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
    /**
     * @var DummyTransformer
     */
    private $transformer;

    /**
     *
     */
    protected function setUp()
    {
        $this->transformer = new DummyTransformer([]);
    }

    /**
     *
     */
    public function testUnserializeThrowsException()
    {
        $this->setExpectedException(TransformerException::class);
        $this->transformer->unserialize(new \DateTime());
    }

    /**
     *
     */
    public function testFirstUrl()
    {
        $this->transformer->setFirstUrl('/api/post?page=1');
        $this->assertEquals('/api/post?page=1', $this->transformer->getFirstUrl());
    }

    /**
     *
     */
    public function testLastUrl()
    {
        $this->transformer->setLastUrl('/api/post?page=10');
        $this->assertEquals('/api/post?page=10', $this->transformer->getLastUrl());
    }

    /**
     *
     */
    public function testPrevUrl()
    {
        $this->transformer->setPrevUrl('/api/post?page=2');
        $this->assertEquals('/api/post?page=2', $this->transformer->getPrevUrl());
    }

    /**
     *
     */
    public function testNextUrl()
    {
        $this->transformer->setNextUrl('/api/post?page=3');
        $this->assertEquals('/api/post?page=3', $this->transformer->getNextUrl());
    }
}
