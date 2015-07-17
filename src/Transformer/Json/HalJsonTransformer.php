<?php

namespace NilPortugues\Api\Transformer\Json;

use NilPortugues\Api\Transformer\AbstractTransformer;
use NilPortugues\Serializer\Serializer;

/**
 * This Transformer follows the JSON+HAL specification.
 *
 * @link http://stateless.co/hal_specification.html
 */
class HalJsonTransformer extends AbstractTransformer
{
    /**
     * @var
     */
    private $curies = [];

    /**
     * @param array $curies
     */
    public function setCuries(array $curies)
    {
        $this->curies = array_merge($this->curies, $curies);
    }

    /**
     * @param       $name
     * @param array $curie
     */
    public function addCurie($name, array $curie)
    {
        $this->curies[$name] = $curie;
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    public function serialize($value)
    {
        $this->recursiveSetValues($value);
        $this->groupValuesOrMoveOneLevelUp($value);

        $this->recursiveUnset($value, ['@type']);

        return json_encode(
            array_merge(
                $value,
                [
                    '_links' => [
                        'self' => [
                            'href' => $this->selfUrl,
                        ],
                        'curies' => $this->curies,
                        'first' => [
                            'href' => $this->firstUrl,
                        ],
                        'last' => [
                            'href' => $this->lastUrl,
                        ],
                        'next' => [
                            'href' => $this->nextUrl,
                        ],
                        'prev' => [
                            'href' => $this->prevUrl,
                        ],
                    ],
                    '_embedded' => [],
                ]
            ),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    /**
     * @param array $array
     */
    private function groupValuesOrMoveOneLevelUp(array &$array)
    {
        $keys = [];
        $data = [];
        foreach ($array as $value) {
            if (is_array($value) && array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, $value)) {
                $keys[] = $value[Serializer::CLASS_IDENTIFIER_KEY];
            } else {
                $data[$this->namespaceAsArrayKey($value[Serializer::CLASS_IDENTIFIER_KEY])] = $value;
                $keys[] = null;
            }
        }
        $keys = array_unique($keys);

        if (1 === count($keys)) {
            $keyName = reset($keys);
            $array = [$this->namespaceAsArrayKey($keyName) => $array];
        } else {
            $array = $data;
        }
    }
}
