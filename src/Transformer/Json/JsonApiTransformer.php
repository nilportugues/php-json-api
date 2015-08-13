<?php

namespace NilPortugues\Api\Transformer\Json;

use NilPortugues\Api\Transformer\Helpers\RecursiveDeleteHelper;
use NilPortugues\Api\Transformer\Helpers\RecursiveFilterHelper;
use NilPortugues\Api\Transformer\Helpers\RecursiveFormatterHelper;
use NilPortugues\Api\Transformer\Helpers\RecursiveRenamerHelper;
use NilPortugues\Api\Transformer\Json\Helpers\JsonApi\DataAttributesHelper;
use NilPortugues\Api\Transformer\Json\Helpers\JsonApi\DataIncludedHelper;
use NilPortugues\Api\Transformer\Json\Helpers\JsonApi\DataLinksHelper;
use NilPortugues\Api\Transformer\Json\Helpers\JsonApi\PropertyHelper;
use NilPortugues\Api\Transformer\Transformer;
use NilPortugues\Api\Transformer\TransformerException;
use NilPortugues\Serializer\Serializer;

/**
 * This Transformer follows the http://JsonApi.org specification.
 *
 * @link http://JsonApi.org/format/#document-structure
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
    const RELATED_LINK = 'related';
    const FIRST_LINK = 'first';
    const LAST_LINK = 'last';
    const PREV_LINK = 'prev';
    const NEXT_LINK = 'next';

    /**
     * @param array $value
     *
     * @throws \NilPortugues\Api\Transformer\TransformerException
     *
     * @return string
     */
    public function serialize($value)
    {
        if (empty($this->mappings) || !is_array($this->mappings)) {
            throw new TransformerException(
                'No mappings were found. Mappings are required by the transformer to work.'
            );
        }

        if (is_array($value) && !empty($value[Serializer::MAP_TYPE])) {
            $data = [];
            unset($value[Serializer::MAP_TYPE]);
            foreach ($value[Serializer::SCALAR_VALUE] as $v) {
                $data[] = $this->serializeObject($v);
            }
        } else {
            $data = $this->serializeObject($value);
        }

        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param array $value
     *
     * @return array
     */
    private function serializeObject(array $value)
    {
        $value = $this->preSerialization($value);
        $data = $this->serialization($value);

        return $this->postSerialization($data);
    }

    /**
     * @param array $value
     *
     * @return array
     */
    private function preSerialization(array $value)
    {
        /** @var \NilPortugues\Api\Mapping\Mapping $mapping */
        foreach ($this->mappings as $class => $mapping) {
            RecursiveFilterHelper::deletePropertiesNotInFilter($this->mappings, $value, $class);
            RecursiveDeleteHelper::deleteProperties($this->mappings, $value, $class);
            RecursiveRenamerHelper::renameKeyValue($this->mappings, $value, $class);
        }

        return $value;
    }

    /**
     * @param array $value
     *
     * @return array
     */
    private function serialization(array &$value)
    {
        $data = [
            self::DATA_KEY => array_merge(
                PropertyHelper::setResponseDataTypeAndId($this->mappings, $value),
                DataAttributesHelper::setResponseDataAttributes($this->mappings, $value),
                DataLinksHelper::setResponseDataLinks($this->mappings, $value),
                DataLinksHelper::setResponseDataRelationship($this->mappings, $value, $value)
            ),
        ];

        DataIncludedHelper::setResponseDataIncluded($this->mappings, $this->removeTypeAndId($value), $data);
        $this->setResponseLinks($value, $data);
        $this->setResponseMeta($data);
        $this->setResponseVersion($data);

        return $data;
    }

    /**
     * @param array $copy
     *
     * @return array
     */
    private function removeTypeAndId(array $copy)
    {
        $type = $copy[Serializer::CLASS_IDENTIFIER_KEY];

        foreach ($this->mappings[$type]->getIdProperties() as $propertyName) {
            unset($copy[$propertyName]);
        }
        unset($copy[Serializer::CLASS_IDENTIFIER_KEY]);

        return $copy;
    }

    /**
     * @param array $value
     * @param array $data
     */
    private function setResponseLinks(array $value, array &$data)
    {
        if (!empty($value[Serializer::CLASS_IDENTIFIER_KEY])) {
            $links = array_filter(
                [
                    self::SELF_LINK => $this->getSelfUrl(),
                    self::FIRST_LINK => $this->getFirstUrl(),
                    self::LAST_LINK => $this->getLastUrl(),
                    self::PREV_LINK => $this->getPrevUrl(),
                    self::NEXT_LINK => $this->getNextUrl(),
                ]
            );

            if (!empty($links)) {
                $data[self::LINKS_KEY] = $links;
            }
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
        $response[self::JSON_API_KEY][self::VERSION_KEY] = '1.0';
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function postSerialization(array $data)
    {
        RecursiveFormatterHelper::formatScalarValues($data);
        RecursiveDeleteHelper::deleteKeys($data, [Serializer::CLASS_IDENTIFIER_KEY]);

        return $data;
    }
}
