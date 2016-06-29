<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 8/1/15
 * Time: 12:27 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Http\Request;

use NilPortugues\Api\JsonApi\Http\Request\Request;
use Zend\Diactoros\ServerRequestFactory;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Request
     */
    private $request;

    /**
     *
     */
    protected function setUp()
    {
        $_GET = [
            'fields' => ['user' => 'user_name,email'],
            'filter' => ['user' => 'filter'],
            'include' => 'friends.username,comments',
            'sort' => '-age,gender',
            'page' => [
                'number' => 1,
                'limit' => 100,
                'size' => 20,
                'cursor' => 'abc',
                'offset' => '50a',
            ],
        ];

        $frameworkRequestObject = ServerRequestFactory::fromGlobals(null, $_GET);
        $this->request = new Request($frameworkRequestObject);
    }

    public function testGetPageNumber()
    {
        $this->assertEquals(1, $this->request->getPage()->number());
    }

    public function testGetIncludedRelationships()
    {
        $expected = [
            'friends' => ['username'],
            'comments' => 'comments',
        ];

        $this->assertEquals($expected, $this->request->getIncludedRelationships()->get());
    }

    public function testGetSortFields()
    {
        $this->assertEquals(['age', 'gender'], $this->request->getSort()->fields());
    }

    public function testGetSortDirection()
    {
        $this->assertEquals(['age' => 'descending', 'gender' => 'ascending'], $this->request->getSort()->sorting());
    }

    public function testGetPageLimit()
    {
        $this->assertEquals(100, $this->request->getPage()->limit());
    }

    public function testGetPageOffset()
    {
        $this->assertEquals('50a', $this->request->getPage()->offset());
    }

    public function testGetPageSize()
    {
        $this->assertEquals(20, $this->request->getPage()->size());
    }

    public function testGetPageCursor()
    {
        $this->assertEquals('abc', $this->request->getPage()->cursor());
    }

    public function testGetFields()
    {
        $this->assertEquals(['user' => ['user_name', 'email']], $this->request->getFields()->get());
    }

    public function testGetFilter()
    {
        $this->assertSame(['user' => 'filter'], $this->request->getFilters());
    }
}
