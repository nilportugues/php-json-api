<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/28/15
 * Time: 12:12 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\JsonApi\Server\Query;

use NilPortugues\Api\JsonApi\Http\Factory\RequestFactory;
use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;
use NilPortugues\Api\JsonApi\Server\Errors\InvalidParameterError;
use NilPortugues\Api\JsonApi\Server\Errors\InvalidParameterMemberError;

/**
 * Class QueryObject.
 */
class QueryObject
{
    /**
     * @param JsonApiSerializer $serializer
     * @param ErrorBag          $errorBag
     *
     * @throws QueryException
     */
    public static function assert(JsonApiSerializer $serializer, ErrorBag $errorBag)
    {
        $apiRequest = RequestFactory::create();
        self::validateQueryParamsTypes($serializer, $apiRequest->getFields(), 'Fields', $errorBag);
        self::validateQueryParamsTypes($serializer, $apiRequest->getIncludedRelationships(), 'Include', $errorBag);

        if ($errorBag->count() > 0) {
            throw new QueryException();
        }
    }

    /**
     * @param JsonApiSerializer $serializer
     * @param array             $fields
     * @param                   $paramName
     * @param ErrorBag          $errorBag
     */
    private static function validateQueryParamsTypes(
        JsonApiSerializer $serializer,
        array $fields,
        $paramName,
        ErrorBag $errorBag
    ) {
        if (!empty($fields)) {
            $transformer = $serializer->getTransformer();
            $validateFields = array_keys($fields);

            foreach ($validateFields as $key => $field) {
                $mapping = $transformer->getMappingByAlias($field);
                if (null !== $mapping) {
                    $properties = array_merge(
                        array_combine($mapping->getProperties(), $mapping->getProperties()),
                        $mapping->getAliasedProperties()
                    );

                    $invalidProperties = array_diff($fields[$field], $properties);
                    foreach ($invalidProperties as $extraField) {
                        $errorBag[] = new InvalidParameterMemberError($extraField, $field, strtolower($paramName));
                    }
                    unset($validateFields[$key]);
                }
            }

            if (false === empty($validateFields)) {
                foreach ($validateFields as $field) {
                    $errorBag[] = new InvalidParameterError($field, strtolower($paramName));
                }
            }
        }
    }
}
