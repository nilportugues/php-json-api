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
 * Class InvalidSortError.
 */
class InvalidSortError extends Error
{
    /**
     * @param string $paramName
     */
    public function __construct($paramName)
    {
        parent::__construct(
            'Invalid Sort Parameter',
            sprintf('Sorting by `%s` not supported.', $paramName),
            'bad_request'
        );

        $this->setSource('parameter', 'sort');
    }
}
