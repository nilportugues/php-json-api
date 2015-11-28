<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/28/15
 * Time: 10:37 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\JsonApi\Http\Request;

use NilPortugues\Api\JsonApi\Http\Factory\RequestFactory;

/**
 * Class RequestTrait.
 */
trait RequestTrait
{
    /**
     * @return Request
     */
    protected function apiRequest()
    {
        return RequestFactory::create();
    }
}
