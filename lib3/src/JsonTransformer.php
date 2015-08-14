<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/18/15
 * Time: 2:26 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\Json;

use NilPortugues\Api\Mapping\Mapper;
use NilPortugues\Api\Transformer\Helpers\RecursiveDeleteHelper;
use NilPortugues\Api\Transformer\Helpers\RecursiveRenamerHelper;
use NilPortugues\Api\Transformer\Transformer;
use NilPortugues\Serializer\Serializer;

/**
 * Class JsonTransformer.
 */
class JsonTransformer extends Transformer
{
    const META_KEY = 'meta';
    const LINKS = 'links';

    /**
     * @param Mapper $mapper
     */
    public function __construct(Mapper $mapper = null)
    {
        if (null !== $mapper) {
            $this->mappings = $mapper->getClassMap();
        }
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    public function serialize($value)
    {
        return json_encode($this->serialization($value), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    protected function serialization($value)
    {
        if (null !== $this->mappings) {
            /** @var \NilPortugues\Api\Mapping\Mapping $mapping */
            foreach ($this->mappings as $class => $mapping) {
                RecursiveDeleteHelper::deleteProperties($this->mappings, $value, $class);
                RecursiveRenamerHelper::renameKeyValue($this->mappings, $value, $class);
            }
        }

        $this->setResponseMeta($value);
        $this->setResponseLinks($value);
        self::formatScalarValues($value);
        RecursiveDeleteHelper::deleteKeys($value, [Serializer::CLASS_IDENTIFIER_KEY]);
        self::flattenObjectsWithSingleKeyScalars($value);
        $this->recursiveSetKeysToUnderScore($value);

        return $value;
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
    private function setResponseLinks(array &$response)
    {
        // print_r($this->buildLinks());

        $links = array_filter(
            array_merge(
                $this->buildLinks(),
                $this->getResponseAdditionalLinks($response, $response[Serializer::CLASS_IDENTIFIER_KEY])
            )
        );

        if (!empty($links)) {
            $response[self::LINKS] = $this->addHrefToLinks($links);
        }
    }
}
