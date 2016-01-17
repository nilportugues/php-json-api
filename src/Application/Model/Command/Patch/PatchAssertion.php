<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 17/01/16
 * Time: 12:57.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Server\Data;

class PatchAssertion
{
    /**
     * @var DataFormatAssertion
     */
    private $inputValidation;

    /**
     * PatchSpecification constructor.
     *
     * @param DataFormatAssertion $inputValidation
     */
    public function __construct(DataFormatAssertion $inputValidation)
    {
        $this->inputValidation = $inputValidation;
    }

    /**
     * @param array  $data
     * @param string $className
     */
    public function assert($data, $className)
    {
        $this->inputValidation->assert($data, $className);
    }
}
