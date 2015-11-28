<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 8/1/15
 * Time: 12:27 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Tests\Api\JsonApi\Http\Response\JsonApi;

use NilPortugues\Api\JsonApi\Http\Response\ResourceCreated;

class ResourceCreatedTest extends \PHPUnit_Framework_TestCase
{
    public function testResponseWithLocation()
    {
        $json = \json_encode([
            'data' => [
                    'type' => 'photos',
                    'id' => '550e8400-e29b-41d4-a716-446655440000',
                    'attributes' => [
                        'title' => 'Ember Hamster',
                        'src' => 'http://example.com/images/productivity.png',
                    ],
                    'links' => [
                        'self' => 'http://example.com/photos/550e8400-e29b-41d4-a716-446655440000',
                    ],
                ],
        ], JSON_UNESCAPED_SLASHES);

        $response = new ResourceCreated($json);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(['application/vnd.api+json'], $response->getHeader('Content-type'));
        $this->assertEquals(['http://example.com/photos/550e8400-e29b-41d4-a716-446655440000'], $response->getHeader('Location'));
    }

    public function testResponse()
    {
        $json = \json_encode([]);
        $response = new ResourceCreated($json);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(['application/vnd.api+json'], $response->getHeader('Content-type'));
    }
}
