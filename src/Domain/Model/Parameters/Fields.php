<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/14/15
 * Time: 7:13 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Domain\Model\Parameters;

class Fields
{
    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @param string $type
     * @param string $fieldName
     */
    public function addField($type, $fieldName)
    {
        $this->fields[(string) $type][] = (string) $fieldName;
    }

    /**
     * @return array
     */
    public function get()
    {
        return $this->fields;
    }

    /**
     * @return string[]
     */
    public function types()
    {
        return array_keys($this->fields);
    }

    /**
     * @param string $type
     *
     * @return array
     */
    public function members($type)
    {
        return (array_key_exists($type, $this->fields)) ? $this->fields[$type] : [];
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return 0 === count($this->fields);
    }
}
