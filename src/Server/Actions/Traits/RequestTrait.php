<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/14/15
 * Time: 11:46 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Server\Actions\Traits;

use NilPortugues\Api\JsonApi\Http\Request\Parameters\Fields;
use NilPortugues\Api\JsonApi\Http\Request\Parameters\Included;
use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\Server\Errors\Error;
use NilPortugues\Api\JsonApi\Server\Errors\InvalidParameterError;
use NilPortugues\Api\JsonApi\Server\Errors\InvalidParameterMemberError;

/**
 * Class RequestTrait.
 */
trait RequestTrait
{
    /**
     * @var Error[]
     */
    private $queryParamErrorBag = [];

    /**
     * @return Error[]
     */
    protected function getQueryParamsErrors()
    {
        return $this->queryParamErrorBag;
    }

    /**
     * @param JsonApiSerializer $serializer
     * @param Fields            $fields
     * @param Included          $included
     *
     * @return bool
     */
    protected function hasValidQueryParams($serializer, Fields $fields, Included $included)
    {
        $this->validateQueryParamsTypes($serializer, $fields, 'Fields');
        $this->validateQueryParamsTypes($serializer, $included, 'Include');

        return empty($this->queryParamErrorBag);
    }

    /**
     * @param JsonApiSerializer $serializer
     * @param Fields            $fields
     * @param                   $paramName
     */
    private function validateQueryParamsTypes($serializer, Fields $fields, $paramName)
    {
        if (false === $fields->isEmpty()) {
            $transformer = $serializer->getTransformer();
            $validateFields = $fields->types();

            foreach ($validateFields as $key => $field) {
                $mapping = $transformer->getMappingByAlias($field);
                if (null !== $mapping) {
                    $properties = array_merge(
                        array_combine($mapping->getProperties(), $mapping->getProperties()),
                        $mapping->getAliasedProperties()
                    );

                    $invalidProperties = array_diff($fields->members($field), $properties);
                    foreach ($invalidProperties as $extraField) {
                        $this->queryParamErrorBag[] = new InvalidParameterMemberError($extraField, $field, strtolower(
                            $paramName
                        ));
                    }
                    unset($validateFields[$key]);
                }
            }

            if (false === empty($validateFields)) {
                foreach ($validateFields as $field) {
                    $this->queryParamErrorBag[] = new InvalidParameterError($field, strtolower($paramName));
                }
            }
        }
    }
}
