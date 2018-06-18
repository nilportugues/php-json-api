<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/15/15
 * Time: 4:29 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Behaviour\Server\Actions\Traits;

use NilPortugues\Api\JsonApi\Http\Request\Parameters\Fields;
use NilPortugues\Api\JsonApi\Http\Request\Parameters\Included;
use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\JsonApiTransformer;
use NilPortugues\Api\JsonApi\Server\Actions\Traits\RequestTrait;
use NilPortugues\Api\Mapping\Mapper;
use NilPortugues\Tests\Api\JsonApi\Behaviour\HelperMapping;

class RequestTraitTest extends \PHPUnit_Framework_TestCase
{
    use RequestTrait;

    /**
     * @var JsonApiSerializer
     */
    private $serializer;
    /**
     * @var Fields
     */
    private $fields;
    /**
     * @var Included
     */
    private $included;

    /**
     *
     */
    public function setUp()
    {
        $this->included = new Included();
        $this->included->add('post.user_comment'); //will cause error

        $this->fields = new Fields();
        $this->fields->addField('post', 'title');
        $this->fields->addField('blog', 'post');  //will cause error

        $mappings = HelperMapping::complex();
        $this->serializer = new JsonApiSerializer(new JsonApiTransformer(new Mapper($mappings)));
    }

    /**
     *
     */
    public function testItCanGetQueryParamErrors()
    {
        $this->assertEmpty($this->getQueryParamsErrors());
    }

    /**
     *
     */
    public function testItHasValidQueryParams()
    {
        $isValid = $this->hasValidQueryParams($this->serializer, $this->fields, $this->included);

        $this->assertFalse($isValid);
    }
}
