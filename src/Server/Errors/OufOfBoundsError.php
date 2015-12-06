<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/28/15
 * Time: 1:24 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Server\Errors;

/**
 * Class OufOfBoundsError.
 */
class OufOfBoundsError extends Error
{
    /**
     * @param string $pageNumber
     * @param string $pageSize
     */
    public function __construct($pageNumber, $pageSize)
    {
        parent::__construct(
            'Ouf Of Bounds',
            sprintf('Page %s of size %s was not found.', $pageNumber, $pageSize),
            'out_of_bounds'
        );

        $this->setSource('parameter', 'page');
    }
}
