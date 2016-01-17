<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/28/15
 * Time: 1:28 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Domain\Model\Errors;

/**
 * Class InvalidAttributeError.
 */
class InvalidAttributeError extends Error
{
    /**
     * @param string $property
     * @param string $type
     */
    public function __construct($property, $type)
    {
        parent::__construct(
            'Invalid Attribute',
            sprintf('Attribute `%s` for resource `%s` is not valid.', $property, $type),
            'bad_request'
        );

        $this->setSource('pointer', '/data/attributes');
    }
}
