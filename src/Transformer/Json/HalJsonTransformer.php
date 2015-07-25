<?php

namespace NilPortugues\Api\Transformer\Json;

use NilPortugues\Api\Transformer\Transformer;

/**
 * This Transformer follows the JSON+HAL specification.
 *
 * @link http://stateless.co/hal_specification.html
 */
class HalJsonTransformer extends Transformer
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
    }
}
