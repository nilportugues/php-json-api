<?php

namespace NilPortugues\Api\Transformer;

use NilPortugues\Api\Mapping\Mapper;
use NilPortugues\Api\Mapping\Mapping;
use NilPortugues\Api\Transformer\Helpers\RecursiveFormatterHelper;
use NilPortugues\Serializer\Serializer;
use NilPortugues\Serializer\Strategy\StrategyInterface;

abstract class Transformer implements StrategyInterface
{
    const SELF_LINK = 'self';
    const FIRST_LINK = 'first';
    const LAST_LINK = 'last';
    const PREV_LINK = 'prev';
    const NEXT_LINK = 'next';
    const LINKS_HREF = 'href';
    const LINKS_KEY = 'links';

    /**
     * @var Mapping[]
     */
    protected $mappings = [];
    /**
     * @var string
     */
    protected $firstUrl = '';
    /**
     * @var string
     */
    protected $lastUrl = '';
    /**
     * @var string
     */
    protected $prevUrl = '';
    /**
     * @var string
     */
    protected $nextUrl = '';

    /**
     * @var string
     */
    protected $selfUrl = '';

    /**
     * @var array
     */
    protected $meta = [];

    /**
     * @param Mapper $mapper
     */
    public function __construct(Mapper $mapper)
    {
        $this->mappings = $mapper->getClassMap();
    }

    /**
     * Represents the provided $value as a serialized value in string format.
     *
     * @param mixed $value
     *
     * @return string
     */
    abstract public function serialize($value);

    /**
     * Unserialization will fail. This is a transformer.
     *
     * @param string $value
     *
     * @throws TransformerException
     *
     * @return array
     */
    public function unserialize($value)
    {
        throw new TransformerException(sprintf('%s does not perform unserializations.', __CLASS__));
    }

    /**
     * @param string       $key
     * @param array|string $value
     */
    public function addMeta($key, $value)
    {
        $this->meta[$key] = $value;
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
     * @param array $links
     *
     * @return array
     */
    protected function addHrefToLinks(array $links)
    {
        if (!empty($links)) {
            foreach ($links as &$link) {
                $link = [self::LINKS_HREF => $link];
            }
        }

        return $links;
    }

    /**
     * @throws TransformerException
     */
    protected function noMappingGuard()
    {
        if (empty($this->mappings) || !is_array($this->mappings)) {
            throw new TransformerException(
                'No mappings were found. Mappings are required by the transformer to work.'
            );
        }
    }

    /**
     * Changes all array keys to under_score format using recursion.
     *
     * @param array $array
     */
    protected function recursiveSetKeysToUnderScore(array &$array)
    {
        $newArray = [];
        foreach ($array as $key => &$value) {
            $underscoreKey = RecursiveFormatterHelper::camelCaseToUnderscore($key);
            $newArray[$underscoreKey] = $value;

            if (is_array($value)) {
                $this->recursiveSetKeysToUnderScore($newArray[$underscoreKey]);
            }
        }
        $array = $newArray;
    }

    /**
     * @return array
     */
    protected function buildLinks()
    {
        $links = array_filter(
            [
                self::SELF_LINK => $this->getSelfUrl(),
                self::FIRST_LINK => $this->getFirstUrl(),
                self::LAST_LINK => $this->getLastUrl(),
                self::PREV_LINK => $this->getPrevUrl(),
                self::NEXT_LINK => $this->getNextUrl(),
            ]
        );

        return $links;
    }

    /**
     * @return string
     */
    public function getSelfUrl()
    {
        return $this->selfUrl;
    }

    /**
     * @param string $selfUrl
     *
     * @return $this
     */
    public function setSelfUrl($selfUrl)
    {
        $this->selfUrl = $selfUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstUrl()
    {
        return $this->firstUrl;
    }

    /**
     * @param string $firstUrl
     *
     * @throws \InvalidArgumentException
     */
    public function setFirstUrl($firstUrl)
    {
        $this->firstUrl = (string) $firstUrl;
    }

    /**
     * @return string
     */
    public function getLastUrl()
    {
        return $this->lastUrl;
    }

    /**
     * @param string $lastUrl
     *
     * @throws \InvalidArgumentException
     */
    public function setLastUrl($lastUrl)
    {
        $this->lastUrl = (string) $lastUrl;
    }

    /**
     * @return string
     */
    public function getPrevUrl()
    {
        return $this->prevUrl;
    }

    /**
     * @param string $prevUrl
     *
     * @throws \InvalidArgumentException
     */
    public function setPrevUrl($prevUrl)
    {
        $this->prevUrl = (string) $prevUrl;
    }

    /**
     * @return string
     */
    public function getNextUrl()
    {
        return $this->nextUrl;
    }

    /**
     * @param string $nextUrl
     *
     * @throws \InvalidArgumentException
     */
    public function setNextUrl($nextUrl)
    {
        $this->nextUrl = (string) $nextUrl;
    }

    /**
     * @param array  $copy
     * @param string $type
     *
     * @return array
     */
    protected function getResponseAdditionalLinks(array $copy, $type)
    {
        if (!empty($this->mappings[$type])) {
            $otherUrls = $this->mappings[$type]->getUrls();
            list($idValues, $idProperties) = RecursiveFormatterHelper::getIdPropertyAndValues(
                $this->mappings,
                $copy,
                $type
            );

            return str_replace($idProperties, $idValues, $otherUrls);
        }

        return [];
    }

    /**
     * Replaces the Serializer array structure representing scalar values to the actual scalar value using recursion.
     *
     * @param array $array
     */
    protected static function formatScalarValues(array &$array)
    {
        $array = self::arrayToScalarValue($array);

        if (is_array($array) && !array_key_exists(Serializer::SCALAR_VALUE, $array)) {
            self::loopScalarValues($array, 'formatScalarValues');
        }
    }

    /**
     * @param array $array
     *
     * @return array
     */
    protected static function arrayToScalarValue(array &$array)
    {
        if (array_key_exists(Serializer::SCALAR_VALUE, $array)) {
            $array = $array[Serializer::SCALAR_VALUE];
        }

        return $array;
    }

    /**
     * @param array  $array
     * @param string $method
     */
    protected static function loopScalarValues(array &$array, $method)
    {
        foreach ($array as $propertyName => &$value) {
            if (is_array($value) && self::LINKS_KEY !== $propertyName) {
                self::$method($value);
            }
        }
    }

    /**
     * Simplifies the data structure by removing an array level if data is scalar and has one element in array.
     *
     * @param array $array
     */
    protected static function flattenObjectsWithSingleKeyScalars(array &$array)
    {
        if (1 === count($array) && is_scalar(end($array))) {
            $array = array_pop($array);
        }

        if (is_array($array)) {
            self::loopScalarValues($array, 'flattenObjectsWithSingleKeyScalars');
        }
    }
}
