<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/29/15
 * Time: 12:47 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\JsonApi\Http\Response;

/**
 * Class Response.
 */
class Response extends AbstractResponse
{
    /**
     * @var int
     */
    protected $httpCode = 200;

    /**
     * @var array
     */
    protected $links = [
        'next' => 'next',
        'last' => 'last',
        'first' => 'first',
        'previous' => 'prev',
    ];

    /**
     * @param string $json
     */
    public function __construct($json)
    {
        $pagination = json_decode($json, true);
        if (!empty($pagination['links'])) {
            $headerLinks = [];
            foreach ($this->links as $linkName => $relName) {
                if (!empty($pagination['links'][$linkName]['href'])) {
                    $headerLinks[] = sprintf('<%s>; rel="%s"', $pagination['links'][$linkName]['href'], $relName);
                }
            }

            if (!empty($headerLinks)) {
                $this->headers['Link'] = implode(', ', $headerLinks);
            }
        }

        parent::__construct($json);
    }
}
