<?php

namespace NilPortugues\Api\Transformer\Json;

use NilPortugues\Api\Transformer\Helpers\RecursiveDeleteHelper;
use NilPortugues\Api\Transformer\Helpers\RecursiveFilterHelper;
use NilPortugues\Api\Transformer\Helpers\RecursiveRenamerHelper;
use NilPortugues\Api\Transformer\Transformer;
use NilPortugues\Serializer\Serializer;

/**
 * This Transformer follows the JSON+HAL specification.
 *
 * @link http://stateless.co/hal_specification.html
 */
class HalJsonTransformer extends Transformer
{
    const EMBEDDED_KEY = '_embedded';

    const LINKS_KEY = '_links';
    const LINKS_TEMPLATED_KEY = 'templated';
    const LINKS_DEPRECATION_KEY = 'deprecation';
    const LINKS_TYPE_KEY = 'type';
    const LINKS_NAME_KEY = 'name';
    const LINKS_PROFILE_KEY = 'profile';
    const LINKS_TITLE_KEY = 'title';
    const LINKS_HREF_LANG_KEY = 'hreflang';
    const LINKS_HREF = 'href';

    const MEDIA_PROFILE_KEY = 'profile';

    const SELF_LINK = 'self';
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
     * @param array $data
     *
     * @return array
     */
    private function serialization(array &$data)
    {
        return $data;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function postSerialization(array $data)
    {
        $this->recursiveSetKeysToUnderScore($data);

        return $data;
    }
}
