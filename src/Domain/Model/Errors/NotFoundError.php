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
 * Class NotFoundError.
 */
class NotFoundError extends Error
{
    /**
     * @param string $type
     * @param string $id
     */
    public function __construct($type, $id)
    {
        $type = ucwords(str_replace('_', ' ', $type));

        parent::__construct(
            sprintf('%s Not Found', $type),
            sprintf('%s with id %s was not found.', $type, $id),
            'resource_not_found'
        );
    }
}
