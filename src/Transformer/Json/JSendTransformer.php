<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/18/15
 * Time: 2:26 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\Transformer\Json;

/**
 * Class JsonTransformer.
 */
class JSendTransformer extends JsonTransformer
{
    /**
     * @param $value
     *
     * @return mixed
     */
    protected function serialization($value)
    {
        $value = parent::serialization($value);
        $data = [];

        if (!empty($value[self::META_KEY])) {
            $data[self::META_KEY] = $value[self::META_KEY];
            unset($value[self::META_KEY]);
        }

        if (!empty($value[self::LINKS])) {
            $data[self::LINKS] = $value[self::LINKS];
            unset($value[self::LINKS]);
        }

        $data = array_merge(['data' => $value], $data);

        return $data;
    }
}
