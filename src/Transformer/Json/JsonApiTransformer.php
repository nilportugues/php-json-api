<?php

namespace NilPortugues\Api\Transformer\Json;

use NilPortugues\Api\Transformer\Helpers\RecursiveDeleteHelper;
use NilPortugues\Api\Transformer\Helpers\RecursiveFilterHelper;
use NilPortugues\Api\Transformer\Helpers\RecursiveRenamerHelper;
use NilPortugues\Api\Transformer\Json\Helpers\JsonApi\DataAttributesHelper;
use NilPortugues\Api\Transformer\Json\Helpers\JsonApi\DataIncludedHelper;
use NilPortugues\Api\Transformer\Json\Helpers\JsonApi\DataLinksHelper;
use NilPortugues\Api\Transformer\Json\Helpers\JsonApi\PropertyHelper;
use NilPortugues\Api\Transformer\Transformer;
use NilPortugues\Serializer\Serializer;

/**
 * This Transformer follows the http://JsonApi.org specification.
 *
 * @link http://JsonApi.org/format/#document-structure
 */
class JsonApiTransformer extends Transformer
{
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

    /**
     * @param array $value
     *
     * @throws \NilPortugues\Api\Transformer\TransformerException
     *
     * @return string
     */
    public function serialize($value)
    {
        $this->noMappingGuard();

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
    protected function setResponseLinks(array $value, array &$data)
    {
        if (!empty($value[Serializer::CLASS_IDENTIFIER_KEY])) {
            $type = $value[Serializer::CLASS_IDENTIFIER_KEY];
            $urls = $this->mappings[$type]->getUrls();

            $data[self::LINKS_KEY] = array_filter(array_merge(
                $this->addHrefToLinks($this->buildLinks()),
                (!empty($data[self::LINKS_KEY])) ? $data[self::LINKS_KEY] : [],
                (!empty($urls)) ? $this->addHrefToLinks($this->getResponseAdditionalLinks($value, $type)) : []
            ));

            if (empty($data[self::LINKS_KEY])) {
                unset($data[self::LINKS_KEY]);
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
        $this->formatScalarValues($data);
        RecursiveDeleteHelper::deleteKeys($data, [Serializer::CLASS_IDENTIFIER_KEY]);

        return $data;
    }
}
