<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/28/15
 * Time: 1:31 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Server\Errors;

/**
 * Class InvalidParameterError.
 */
class InvalidParameterError extends Error
{
    /**
     * @param string $paramName
     * @param string $field
     */
    public function __construct($paramName, $field)
    {
        parent::__construct(
            'Invalid Parameter',
            sprintf('Parameter `%s` not supported.', $paramName),
            'bad_request'
        );

        $this->setSource('parameter', $field);
    }
}
