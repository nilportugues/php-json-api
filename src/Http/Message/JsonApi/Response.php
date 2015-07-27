<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/28/15
 * Time: 1:20 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\Http\Message\JsonApi;

class Response extends \NilPortugues\Api\Http\Message\Response
{
    /**
     * @var array
     */
    protected $headers = ['Content-type' => 'application/vnd.api+json; charset=utf-8'];
}
