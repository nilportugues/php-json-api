<?php

namespace NilPortugues\Api\Transformer\Json;

use NilPortugues\Api\Transformer\Transformer;
use NilPortugues\Api\Transformer\TransformerException;
use NilPortugues\Serializer\Serializer;

/**
 * This Transformer follows the http://jsonapi.org specification.
 *
 * @link http://jsonapi.org/format/#document-structure
 */
class JsonApiTransformer extends Transformer
{
    const SELF_LINK = 'self';
    const TITLE = 'title';
    const RELATIONSHIPS_KEY = 'relationships';
    const LINKS_KEY = 'links';
    const TYPE_KEY = 'type';
    const DATA_KEY = 'data';
    const JSON_API_KEY = 'jsonapi';
    const META_KEY = 'meta';
    const INCLUDED_KEY = 'included';
    const VERSION_KEY = 'version';
    const ATTRIBUTES_KEY = 'attributes';
    const ID_KEY = 'id';
    const ID_SEPARATOR = '.';

    /**
     * @var array
     */
    private $meta = [];
    /**
     * @var string
     */
    private $apiVersion = '';
    /**
     * @var array
     */
    private $relationships = [];
    /**
     * @var array
     */
    private $included = [];
    /**
     * @var string
     */
    private $relatedUrl = '';

    /**
     * @param string $relatedUrl
     *
     * @return $this
     */
    public function setRelatedUrl($relatedUrl)
    {
        $this->relatedUrl = $relatedUrl;

        return $this;
    }

    /**
     * @param array $included
     *
     * @return $this
     */
    public function setIncluded($included)
    {
        $this->included = $included;

        return $this;
    }

    /**
     * @param array $relationships
     *
     * @return $this
     */
    public function setRelationships($relationships)
    {
        $this->relationships = $relationships;

        return $this;
    }

    /**
     * @param string $apiVersion
     *
     * @return $this
     */
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;

        return $this;
    }

    /**
     * @param array $meta
     *
     * @return $this
     */
    public function setMeta(array $meta)
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * @param array $value
     *
     * @return string
     */
    public function serialize($value)
    {
        $data = [
            self::DATA_KEY => array_merge(
                $this->setResponseDataTypeAndId($value),
                $this->setResponseDataAttributes($value),
                $this->setResponseDataLinks($value),
                $this->setResponseDataRelationship($value)
            ),
        ];

        $copy = $this->removeTypeAndId($value);

        $this->setResponseDataIncluded($copy, $data);
        $this->setResponseLinks($data);
        $this->setResponseMeta($data);
        $this->setResponseVersion($data);

        $this->recursiveSetValues($data);
        $this->recursiveUnset($data, [Serializer::CLASS_IDENTIFIER_KEY]);

        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param array $value
     *
     * @return array
     */
    private function setResponseDataTypeAndId(array &$value)
    {
        $data = [];
        $type = $value[Serializer::CLASS_IDENTIFIER_KEY];
        $data[self::TYPE_KEY] = $this->namespaceAsArrayKey($type);

        $ids = [];
        foreach (array_keys($value) as $propertyName) {
            if (in_array($propertyName, $this->getIdProperties($type), true)) {
                $id = $this->getIdValue($value[$propertyName]);
                $ids[] = (is_array($id)) ? implode(self::ID_SEPARATOR, $id) : $id;
            }
        }

        $data[self::ID_KEY] = implode(self::ID_SEPARATOR, $ids);

        return $data;
    }

    /**
     * @param $type
     *
     * @return array
     */
    private function getIdProperties($type)
    {
        $idProperties = [];

        if (!empty($this->mappings[$type])) {
            $idProperties = $this->mappings[$type]->getIdProperties();
        }

        return $idProperties;
    }

    /**
     * @param array $id
     *
     * @return array
     */
    private function getIdValue(array $id)
    {
        $this->recursiveSetValues($id);
        if (is_array($id)) {
            $this->recursiveUnset($id, [Serializer::CLASS_IDENTIFIER_KEY]);
        }

        return $id;
    }

    /**
     * @param array $array
     *
     * @return array
     */
    private function setResponseDataAttributes(array &$array)
    {
        $data = [];
        $attributes = [];
        $type = $array[Serializer::CLASS_IDENTIFIER_KEY];
        $idProperties = $this->getIdProperties($type);

        foreach ($array as $propertyName => $value) {
            if (in_array($propertyName, $idProperties, true)) {
                continue;
            }

            $keyName = $this->camelCaseToUnderscore($propertyName);

            if ((is_array($value)
                && array_key_exists(Serializer::SCALAR_TYPE, $value)
                && array_key_exists(Serializer::SCALAR_VALUE, $value))
                && empty($this->mappings[$value[Serializer::SCALAR_TYPE]])
            ) {
                $attributes[$keyName] = $value;
                continue;
            }

            if (is_array($value) && !array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, $value)) {
                if ($this->containsClassIdentifierKey($value)) {
                    $attributes[$keyName] = $value;
                }
            }
        }

        $data[self::ATTRIBUTES_KEY] = $attributes;

        return $data;
    }

    /**
     * @param array $input
     * @param bool  $foundIdentifierKey
     *
     * @return bool
     */
    private function containsClassIdentifierKey(array $input, $foundIdentifierKey = false)
    {
        if (!is_array($input)) {
            return $foundIdentifierKey || false;
        }

        if (in_array(Serializer::CLASS_IDENTIFIER_KEY, $input, true)) {
            return true;
        } else {
            if (!empty($input[Serializer::SCALAR_VALUE])) {
                $input = $input[Serializer::SCALAR_VALUE];

                if (is_array($input)) {
                    foreach ($input as $value) {
                        if (is_array($value)) {
                            $foundIdentifierKey = $foundIdentifierKey
                                || $this->containsClassIdentifierKey($value, $foundIdentifierKey);
                        }
                    }
                }
            }
        }

        return !$foundIdentifierKey;
    }

    /**
     * @param array $value
     *
     * @return array
     */
    private function setResponseDataLinks(array &$value)
    {
        $data = [];
        $type = $value[Serializer::CLASS_IDENTIFIER_KEY];

        if (!empty($this->mappings[$type])) {
            $idValues = [];
            $idProperties = $this->getIdProperties($type);
            foreach ($idProperties as &$propertyName) {
                $idValues[] = $this->getIdValue($value[$propertyName]);
                $propertyName = sprintf('{%s}', $propertyName);
            }
            $this->recursiveFlattenOneElementObjectsToScalarType($idValues);

            $selfLink = $this->mappings[$type]->getResourceUrl();
            if (!empty($selfLink)) {
                $data[self::LINKS_KEY][self::SELF_LINK] = str_replace($idProperties, $idValues, $selfLink);
            }
        }

        return $data;
    }

    /**
     * @param array $array
     *
     * @return mixed
     */
    private function setResponseDataRelationship(array &$array)
    {
        $data[self::RELATIONSHIPS_KEY] = [];

        foreach ($array as $propertyName => $value) {
            if (is_array($value) && array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, $value)) {
                $type = $value[Serializer::CLASS_IDENTIFIER_KEY];

                if (!in_array($propertyName, $this->getIdProperties($type), true)) {
                    $data[self::RELATIONSHIPS_KEY][$propertyName] = array_merge(
                        $this->setResponseDataLinks($value),
                        [self::DATA_KEY => $this->setResponseDataTypeAndId($value)]
                    );
                }
            }
        }

        return $data;
    }

    /**
     * @param string $type
     *
     * @throws TransformerException
     */
    protected function hasMappingGuard($type)
    {
        if (empty($this->mappings[$type])) {
            throw new TransformerException(sprintf('Provided type %s has no mapping.', $type));
        }
    }

    /**
     * @param array $copy
     *
     * @return array
     */
    private function removeTypeAndId(array $copy)
    {
        $type = $copy[Serializer::CLASS_IDENTIFIER_KEY];
        $this->hasMappingGuard($type);

        foreach ($this->mappings[$type]->getIdProperties() as $propertyName) {
            unset($copy[$propertyName]);
        }
        unset($copy[Serializer::CLASS_IDENTIFIER_KEY]);

        return $copy;
    }

    /**
     * @param array $array
     * @param array $data
     */
    private function setResponseDataIncluded(array $array, array &$data)
    {
        foreach ($array as $value) {
            if (is_array($value)) {
                if (array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, $value)) {
                    $attributes = [];
                    $relationships = [];

                    $type = $value[Serializer::CLASS_IDENTIFIER_KEY];

                    foreach ($value as $propertyName => $attribute) {
                        if ($this->isAttributeProperty($propertyName, $type)) {
                            if (array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, $attribute)) {
                                $this->setResponseDataIncluded($value, $data);

                                $selfLink = $this->setResponseDataLinks($attribute);

                                $relationships[$propertyName] = array_merge(
                                    $selfLink,
                                    [self::DATA_KEY => [$propertyName => $this->setResponseDataTypeAndId($attribute)]]
                                );

                                continue;
                            }
                            $attributes[$propertyName] = $attribute;
                        }
                    }

                    if (count($attributes) > 0) {
                        $includedData = $this->setResponseDataTypeAndId($value);

                        if (array_key_exists(self::ID_KEY, $includedData) && !empty($includedData[self::ID_KEY])) {
                            $selfLink = $this->setResponseDataLinks($value);

                            $data[self::INCLUDED_KEY][] = array_merge(
                                [
                                    self::TYPE_KEY => $includedData[self::TYPE_KEY],
                                    self::ID_KEY => $includedData[self::ID_KEY],
                                    self::ATTRIBUTES_KEY => $attributes,
                                ],
                                $selfLink,
                                [self::RELATIONSHIPS_KEY => $relationships]
                            );
                        }
                    }

                    continue;
                }

                if (is_array($value)) {
                    foreach ($value as $inArrayValue) {
                        if (is_array($inArrayValue)) {
                            $this->setResponseDataIncluded($inArrayValue, $data);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param $propertyName
     * @param $type
     *
     * @return bool
     */
    private function isAttributeProperty($propertyName, $type)
    {
        return Serializer::CLASS_IDENTIFIER_KEY !== $propertyName
        && !in_array($propertyName, $this->getIdProperties($type));
    }

    /**
     * @param array $data
     */
    private function setResponseLinks(array &$data)
    {
        if (!empty($this->selfUrl)
            || !empty($this->firstUrl)
            || !empty($this->lastUrl)
            || !empty($this->prevUrl)
            || !empty($this->nextUrl)
            || !empty($this->relatedUrl)
        ) {
            $data[self::LINKS_KEY] = [
                self::SELF_LINK => $this->selfUrl,
                'first' => $this->firstUrl,
                'last' => $this->lastUrl,
                'prev' => $this->prevUrl,
                'next' => $this->nextUrl,
                'related' => $this->relatedUrl,
            ];
            $data[self::LINKS_KEY] = array_filter($data[self::LINKS_KEY]);
        }
    }

    /**
     * @param array $response
     */
    private function setResponseMeta(array &$response)
    {
        if (!empty($this->meta)) {
            $response[self::META_KEY] = $this->meta;
        }
    }

    /**
     * @param array $response
     */
    private function setResponseVersion(array &$response)
    {
        if (!empty($this->apiVersion)) {
            $response[self::JSON_API_KEY][self::VERSION_KEY] = $this->apiVersion;
        }
    }

    /**
     * @param string       $key
     * @param array|string $value
     */
    public function addMeta($key, $value)
    {
        $this->meta[$key] = $value;
    }
}
