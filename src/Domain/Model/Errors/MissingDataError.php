<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/28/15
 * Time: 1:29 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Domain\Model\Errors;

/**
 * Class MissingDataError.
 */
class MissingDataError extends Error
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct(
            'Bad Request',
            "Missing `data` Member at document's top level.",
            'bad_request'
        );

        $this->setSource('pointer', '');
    }
}
