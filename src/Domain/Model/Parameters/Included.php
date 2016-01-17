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

/**
 * Class Included.
 */
class Included
{
    /**
     * @var array
     */
    protected $included = [];

    /**
     * @param string $relationship
     */
    public function add($relationship)
    {
        $data = \explode('.', (string) $relationship);
        $type = $data[0];
        $attribute = (!empty($data[1])) ? $data[1] : null;

        if (null === $attribute) {
            $this->included[$type] = $type;
        } else {
            if (!empty($this->included[$type]) && is_string($this->included[$type])) {
                $this->included[$type] = [];
            }
            $this->included[$type][] = $attribute;
        }
    }

    /**
     * @return array
     */
    public function get()
    {
        return $this->included;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return 0 === count($this->included);
    }
}
