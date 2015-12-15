<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/15/15
 * Time: 4:16 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Server\Query;

use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\JsonApiTransformer;
use NilPortugues\Api\Mapping\Mapper;

class QueryObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JsonApiSerializer
     */
    private $serializer;

    public function setUp()
    {
        $mappings = [];
        $this->serializer = new JsonApiSerializer(new JsonApiTransformer(new Mapper($mappings)));
    }

    public function testItCanAssertAndReturnNoErrors()
    {
    }

    public function testItCanAssertAndThrowExceptionForInvalidQueryParamValues()
    {
    }

    public function testItCanAssertAndThrowExceptionForInvalidIncludeParams()
    {
    }

    public function testItCanAssertAndThrowExceptionForInvalidSortParams()
    {
    }
}
