<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/28/15
 * Time: 10:00 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Domain\Model\Errors;

/**
 * Class InvalidTypeError.
 */
class InvalidTypeError extends Error
{
    /**
     * @param string $type
     */
    public function __construct($type)
    {
        parent::__construct(
            'Unsupported Action',
            sprintf('Resource type `%s` not supported.', $type),
            'resource_not_supported'
        );

        $this->setSource('pointer', '/data');
    }
}
