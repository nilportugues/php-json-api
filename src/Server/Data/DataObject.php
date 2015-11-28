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
     */
    public function assert($data, JsonApiSerializer $serializer, $className, ErrorBag $errorBag)
    {
        DataObjectAssertions::assert($data, $serializer, $className, $errorBag);
    }

    /**
     * @param array             $data
     * @param JsonApiSerializer $serializer
     *
     * @return array
     */
    public static function missingCreationAttributes(array $data, JsonApiSerializer $serializer)
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
    public static function undoAliasedAttributes(array $data, JsonApiSerializer $serializer)
    {
        $mapping = $serializer->getTransformer()->getMappingByAlias($data['type']);
        $aliases = $mapping->getAliasedProperties();
        $keys = str_replace(array_values($aliases), array_keys($aliases), array_keys($data['attributes']));

        return array_combine($keys, array_values($data['attributes']));
    }
}
