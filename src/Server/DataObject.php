<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/27/15
 * Time: 9:58 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\JsonApi\Server;

use NilPortugues\Api\JsonApi\Http\Error;
use NilPortugues\Api\JsonApi\Http\ErrorBag;
use NilPortugues\Api\JsonApi\JsonApiSerializer;

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
    public function __construct($data, JsonApiSerializer $serializer, $className, ErrorBag $errorBag)
    {
        $this->assertItIsArray($data, $errorBag);
        $this->assertItHasTypeMember($data, $errorBag);
        $this->assertItTypeMemberIsExpectedValue($data, $serializer, $className, $errorBag);
        $this->assertItHasAttributeMember($data, $errorBag);
        $this->assertAttributesExists($data, $serializer, $errorBag);
    }

    /**
     * @param $data
     * @param ErrorBag $errorBag
     *
     * @throws DataObjectException
     */
    private function assertItIsArray($data, ErrorBag $errorBag)
    {
        if (empty($data) || !is_array($data)) {
            $errorCode = 'bad_request';
            $errorTitle = 'Bad Request';
            $errorMessage = 'Creation resource does not follow the JSON API standard.';
            $errorBag[] = new Error($errorTitle, $errorMessage, $errorCode);
            throw new DataObjectException();
        }
    }

    /**
     * @param array    $data
     * @param ErrorBag $errorBag
     *
     * @throws DataObjectException
     */
    private function assertItHasTypeMember(array $data, ErrorBag $errorBag)
    {
        if (empty($data['type']) || !is_string($data['type'])) {
            $errorCode = 'bad_request';
            $errorTitle = 'Bad Request';
            $errorMessage = sprintf("Creation resource is missing the 'type' member.");
            $errorBag[] = new Error($errorTitle, $errorMessage, $errorCode);
            throw new DataObjectException();
        }
    }

    /**
     * @param array             $data
     * @param JsonApiSerializer $serializer
     * @param                   $className
     * @param ErrorBag          $errorBag
     *
     * @throws DataObjectException
     */
    private function assertItTypeMemberIsExpectedValue(
        array $data,
        JsonApiSerializer $serializer,
        $className,
        ErrorBag $errorBag
    ) {
        $mapping = $serializer->getTransformer()->getMappingByAlias($data['type']);

        if (null === $mapping || $mapping->getClassName() !== $className) {
            $errorCode = 'resource_not_supported';
            $errorTitle = 'Unsupported Action';
            $errorMessage = sprintf('Resource type \'%s\' not supported.', $data['type']);
            $errorBag[] = new Error($errorTitle, $errorMessage, $errorCode);
            throw new DataObjectException();
        }
    }

    /**
     * @param $data
     * @param ErrorBag $errorBag
     *
     * @throws DataObjectException
     */
    private function assertItHasAttributeMember($data, ErrorBag $errorBag)
    {
        if (empty($data['attributes']) || !is_array($data['attributes'])) {
            $errorCode = 'bad_request';
            $errorTitle = 'Bad Request';
            $errorMessage = sprintf("Creation resource is missing the 'attributes' member.");
            $errorBag[] = new Error($errorTitle, $errorMessage, $errorCode);
            throw new DataObjectException();
        }
    }

    /**
     * @param array             $data
     * @param JsonApiSerializer $serializer
     * @param ErrorBag          $errorBag
     *
     * @throws DataObjectException
     */
    private function assertAttributesExists(array $data, JsonApiSerializer $serializer, ErrorBag $errorBag)
    {
        $inputAttributes = array_keys($data['attributes']);

        $mapping = $serializer->getTransformer()->getMappingByAlias($data['type']);
        $properties = str_replace(
            array_keys($mapping->getAliasedProperties()),
            array_values($mapping->getAliasedProperties()),
            $mapping->getProperties()
        );

        //@todo: add source error
        $hasErrors = false;
        foreach ($inputAttributes as $property) {
            if (false === in_array($property, $properties)) {
                $hasErrors = true;
                $errorCode = 'bad_request';
                $errorTitle = 'Invalid Resource Attribute';
                $errorMessage = sprintf("Attribute '%s' for resource '%s' is not valid.", $property, $data['type']);
                $errorBag[] = new Error($errorTitle, $errorMessage, $errorCode);
            }
        }

        if ($hasErrors) {
            throw new DataObjectException();
        }
    }
}
