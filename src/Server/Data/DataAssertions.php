<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/27/15
 * Time: 11:40 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Server\Data;

use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;
use NilPortugues\Api\JsonApi\Server\Errors\InvalidAttributeError;
use NilPortugues\Api\JsonApi\Server\Errors\InvalidTypeError;
use NilPortugues\Api\JsonApi\Server\Errors\MissingAttributesError;
use NilPortugues\Api\JsonApi\Server\Errors\MissingDataError;
use NilPortugues\Api\JsonApi\Server\Errors\MissingTypeError;

/**
 * Class DataAssertions.
 */
class DataAssertions
{
    /**
     * @param array             $data
     * @param JsonApiSerializer $serializer
     * @param string            $className
     * @param ErrorBag          $errorBag
     */
    public static function assert($data, JsonApiSerializer $serializer, $className, ErrorBag $errorBag)
    {
        self::assertItIsArray($data, $errorBag);
        self::assertItHasTypeMember($data, $errorBag);
        self::assertItTypeMemberIsExpectedValue($data, $serializer, $className, $errorBag);
        self::assertItHasAttributeMember($data, $errorBag);
        self::assertAttributesExists($data, $serializer, $errorBag);
    }

    /**
     * @param          $data
     * @param ErrorBag $errorBag
     *
     * @throws DataException
     */
    private static function assertItIsArray($data, ErrorBag $errorBag)
    {
        if (empty($data) || !is_array($data)) {
            $errorBag[] = new MissingDataError();
            throw new DataException();
        }
    }

    /**
     * @param array    $data
     * @param ErrorBag $errorBag
     *
     * @throws DataException
     */
    private static function assertItHasTypeMember(array $data, ErrorBag $errorBag)
    {
        if (empty($data['type']) || !is_string($data['type'])) {
            $errorBag[] = new MissingTypeError();
            throw new DataException();
        }
    }

    /**
     * @param array             $data
     * @param JsonApiSerializer $serializer
     * @param                   $className
     * @param ErrorBag          $errorBag
     *
     * @throws DataException
     */
    private static function assertItTypeMemberIsExpectedValue(
        array $data,
        JsonApiSerializer $serializer,
        $className,
        ErrorBag $errorBag
    ) {
        $mapping = $serializer->getTransformer()->getMappingByAlias($data['type']);

        if (null === $mapping || $mapping->getClassName() !== $className) {
            $errorBag[] = new InvalidTypeError($data['type']);
            throw new DataException();
        }
    }

    /**
     * @param          $data
     * @param ErrorBag $errorBag
     *
     * @throws DataException
     */
    private static function assertItHasAttributeMember($data, ErrorBag $errorBag)
    {
        if (empty($data['attributes']) || !is_array($data['attributes'])) {
            $errorBag[] = new MissingAttributesError();
            throw new DataException();
        }
    }

    /**
     * @param array             $data
     * @param JsonApiSerializer $serializer
     * @param ErrorBag          $errorBag
     *
     * @throws DataException
     */
    private static function assertAttributesExists(array $data, JsonApiSerializer $serializer, ErrorBag $errorBag)
    {
        $inputAttributes = array_keys($data['attributes']);

        $mapping = $serializer->getTransformer()->getMappingByAlias($data['type']);

        $properties = str_replace(
            array_keys($mapping->getAliasedProperties()),
            array_values($mapping->getAliasedProperties()),
            $mapping->getProperties()
        );
        $properties = array_diff($properties, $mapping->getIdProperties());
        $properties = array_diff($properties, $mapping->getHiddenProperties());

        $hasErrors = false;
        foreach ($inputAttributes as $property) {
            if (false === in_array($property, $properties)) {
                $hasErrors = true;
                $errorBag[] = new InvalidAttributeError($property, $data['type']);
            }
        }

        if ($hasErrors) {
            throw new DataException();
        }
    }
}
