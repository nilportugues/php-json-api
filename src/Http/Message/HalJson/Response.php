<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/28/15
 * Time: 1:16 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\Http\Message\HalJson;

class Response extends \NilPortugues\Api\Http\Message\AbstractResponse
{
    /**
     * A HAL Document uses the format described in [RFC4627] and has the media type "application/hal+json".
     *
     * @var array
     */
    protected $headers = ['Content-type' => 'application/hal+json; charset=utf-8'];
}
