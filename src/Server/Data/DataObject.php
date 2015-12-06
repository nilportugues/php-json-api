<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/27/15
 * Time: 9:58 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Server\Data;

use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;
use NilPortugues\Api\JsonApi\Server\Errors\MissingAttributeError;

/**
 * Class DataObject.
 */
class DataObject
{
    /**
     * @param array             $data
     * @param JsonApiSerializer $serializer
     * @param string            $className
     * @param ErrorBag          $errorBag
     *
     * @throws DataException
     */
    public static function assertPost($data, JsonApiSerializer $serializer, $className, ErrorBag $errorBag)
    {
        try {
            DataAssertions::assert($data, $serializer, $className, $errorBag);
        } catch (DataException $e) {
        }

        $missing = self::missingCreationAttributes($data, $serializer);
        if (false === empty($missing)) {
            foreach ($missing as $attribute) {
                $errorBag[] = new MissingAttributeError($attribute);
            }
            throw new DataException();
        }
    }

    /**
     * @param array             $data
     * @param JsonApiSerializer $serializer
     * @param string            $className
     * @param ErrorBag          $errorBag
     *
     * @throws DataException
     */
    public static function assertPut($data, JsonApiSerializer $serializer, $className, ErrorBag $errorBag)
    {
        self::assertPost($data, $serializer, $className, $errorBag);
    }

    /**
     * @param array             $data
     * @param JsonApiSerializer $serializer
     * @param string            $className
     * @param ErrorBag          $errorBag
     */
    public static function assertPatch($data, JsonApiSerializer $serializer, $className, ErrorBag $errorBag)
    {
        DataAssertions::assert($data, $serializer, $className, $errorBag);
    }

    /**
     * @param array             $data
     * @param JsonApiSerializer $serializer
     *
     * @return array
     */
    private static function missingCreationAttributes(array $data, JsonApiSerializer $serializer)
    {
        $inputAttributes = array_keys($data['attributes']);

        $mapping = $serializer->getTransformer()->getMappingByAlias($data['type']);

        $properties = str_replace(
            array_keys($mapping->getAliasedProperties()),
            array_values($mapping->getAliasedProperties()),
            $mapping->getProperties()
        );
        $properties = array_diff($properties, $mapping->getIdProperties());

        $diff = (array) array_diff($properties, $inputAttributes);

        return $diff;
    }

    /**
     * @param array             $data
     * @param JsonApiSerializer $serializer
     *
     * @return array
     */
    public static function getAttributes(array $data, JsonApiSerializer $serializer)
    {
        $mapping = $serializer->getTransformer()->getMappingByAlias($data['type']);
        $aliases = $mapping->getAliasedProperties();
        $keys = str_replace(array_values($aliases), array_keys($aliases), array_keys($data['attributes']));

        return array_combine($keys, array_values($data['attributes']));
    }
}
