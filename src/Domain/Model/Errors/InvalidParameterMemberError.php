<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/28/15
 * Time: 1:31 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Domain\Model\Errors;

/**
 * Class InvalidParameterError.
 */
class InvalidParameterMemberError extends Error
{
    /**
     * @param string $paramName
     * @param string $fieldKey
     * @param string $fieldName
     */
    public function __construct($paramName, $fieldKey, $fieldName)
    {
        parent::__construct(
            'Invalid Parameter',
            sprintf('Parameter member `%s` not supported.', $paramName),
            'bad_request'
        );

        $this->setSource('parameter', sprintf('%s[%s]', $fieldName, $fieldKey));
    }
}
