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

use NilPortugues\Api\JsonApi\Http\Request\Parameters\Fields;
use NilPortugues\Api\JsonApi\Http\Request\Parameters\Included;
use NilPortugues\Api\JsonApi\Http\Request\Parameters\Sorting;
use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\JsonApiTransformer;
use NilPortugues\Api\JsonApi\Domain\Errors\ErrorBag;
use NilPortugues\Api\JsonApi\Server\Query\QueryException;
use NilPortugues\Api\JsonApi\Server\Query\GetAssertion;
use NilPortugues\Api\Mapping\Mapper;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\Post;
use NilPortugues\Tests\Api\JsonApi\HelperMapping;

class QueryObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JsonApiSerializer
     */
    private $serializer;

    public function setUp()
    {
        $this->serializer = new JsonApiSerializer(new JsonApiTransformer(new Mapper(HelperMapping::complex())));
    }

    public function testItCanAssertAndReturnNoErrors()
    {
        $fields = new Fields();
        $included = new Included();
        $sorting = new Sorting();
        $errorBag = new ErrorBag();

        $hasError = false;
        try {
            GetAssertion::assert($fields, $included, $sorting, $errorBag, Post::class);
        } catch (QueryException $e) {
            $hasError = true;
        }
        $this->assertFalse($hasError);
    }

    public function testItCanAssertAndThrowExceptionForInvalidQueryParamValuesWithInvalidMemberName()
    {
        $fields = new Fields();
        $included = new Included();
        $sorting = new Sorting();
        $errorBag = new ErrorBag();

        $hasError = false;
        try {
            $fields->addField('post', 'superpower');
            GetAssertion::assert($fields, $included, $sorting, $errorBag, Post::class);
        } catch (QueryException $e) {
            $hasError = true;
        }
        $this->assertTrue($hasError);
    }
    public function testItCanAssertAndThrowExceptionForInvalidQueryParamValues()
    {
        $fields = new Fields();
        $included = new Included();
        $sorting = new Sorting();
        $errorBag = new ErrorBag();

        $hasError = false;
        try {
            $fields->addField('superhero', 'power');
            GetAssertion::assert($fields, $included, $sorting, $errorBag, Post::class);
        } catch (QueryException $e) {
            $hasError = true;
        }
        $this->assertTrue($hasError);
    }

    public function testItCanAssertAndThrowExceptionForInvalidIncludeParams()
    {
        $fields = new Fields();
        $included = new Included();
        $sorting = new Sorting();
        $errorBag = new ErrorBag();

        $hasError = false;
        try {
            $included->add('superhero');

            GetAssertion::assert($fields, $included, $sorting, $errorBag, Post::class);
        } catch (QueryException $e) {
            $hasError = true;
        }
        $this->assertTrue($hasError);
    }

    public function testItCanAssertAndThrowExceptionForInvalidIncludeParamsWhenIsResourceExplicit()
    {
        $fields = new Fields();
        $included = new Included();
        $sorting = new Sorting();
        $errorBag = new ErrorBag();

        $hasError = false;
        try {
            $included->add('post.superhero');
            $included->add('post.comment');

            GetAssertion::assert($fields, $included, $sorting, $errorBag, Post::class);
        } catch (QueryException $e) {
            $hasError = true;
        }
        $this->assertTrue($hasError);
    }

    public function testItCanAssertAndThrowExceptionForInvalidSortParams()
    {
        $fields = new Fields();
        $included = new Included();
        $sorting = new Sorting();
        $errorBag = new ErrorBag();

        $hasError = false;
        try {
            $sorting->addField('superhero', 'ascending');
            GetAssertion::assert($fields, $included, $sorting, $errorBag, Post::class);
        } catch (QueryException $e) {
            $hasError = true;
        }
        $this->assertTrue($hasError);
    }
}
