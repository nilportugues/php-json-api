<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/14/15
 * Time: 7:11 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\JsonApi\Http\Request\Parameters;

/**
 * Class Sorting.
 */
class Sorting
{
    /**
     * @var array
     */
    protected $sort = [];

    /**
     * @return string
     */
    public function get()
    {
        $get = [];
        foreach ($this->sort as $field => $direction) {
            $get[] = ('descending' === $direction) ? '-'.$field : $field;
        }

        return implode(',', $get);
    }

    /**
     * @param string $field
     * @param string $direction
     */
    public function addField($field, $direction)
    {
        $this->sort[(string) $field] = (string) $direction;
    }

    /**
     * @return array
     */
    public function sorting()
    {
        return $this->sort;
    }

    /**
     * @return array
     */
    public function fields()
    {
        return array_keys($this->sort);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return 0 === count($this->sort);
    }
}
