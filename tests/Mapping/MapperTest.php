<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 8/14/15
 * Time: 1:17 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Tests\Api\Mapping;

use NilPortugues\Api\Mapping\Mapper;
use NilPortugues\Api\Mapping\MappingException;
use NilPortugues\Tests\Api\Dummy\ComplexObject\Post;

class MapperTest extends \PHPUnit_Framework_TestCase
{
    public function testItCanConstruct()
    {
        $mapping = [
            [
                'class' => Post::class,
                'alias' => 'Message',
                'aliased_properties' => [
                    'author' => 'author',
                    'title' => 'headline',
                    'content' => 'body',
                ],
                'hide_properties' => [

                ],
                'id_properties' => [
                    'postId',
                ],
                'urls' => [
                    // Mandatory
                    'self' => 'http://example.com/posts/{postId}',
                    // Optional
                    'comments' => 'http://example.com/posts/{postId}/comments',
                ],
                // (Optional) Used by HAL+JSON
                'curies' => [
                    'name' => 'example',
                    'href' => 'http://example.com/docs/rels/{rel}',
                ],
            ],
        ];

        $mapper = new Mapper($mapping);
        $this->assertNotEmpty($mapper->getClassMap());
    }

    public function testItCanThrowException()
    {
        $this->setExpectedException(MappingException::class);

        $mapping = [
            [
                'class' => Post::class,
                'alias' => 'Message',
                'aliased_properties' => [
                    'author' => 'author',
                    'title' => 'headline',
                    'content' => 'body',
                ],
                'hide_properties' => [

                ],
                'id_properties' => [
                    'postId',
                ],
                'urls' => [
                    // Mandatory
                    'self' => 'http://example.com/posts/{postId}',
                    // Optional
                    'comments' => 'http://example.com/posts/{postId}/comments',
                ],
                // (Optional) Used by HAL+JSON
                'curies' => [
                    'name' => 'example',
                    'href' => 'http://example.com/docs/rels/{rel}',
                ],
            ],
            [
                'class' => Post::class,
                'alias' => 'Message',
                'aliased_properties' => [
                    'author' => 'author',
                    'title' => 'headline',
                    'content' => 'body',
                ],
                'hide_properties' => [

                ],
                'id_properties' => [
                    'postId',
                ],
                'urls' => [
                    // Mandatory
                    'self' => 'http://example.com/posts/{postId}',
                    // Optional
                    'comments' => 'http://example.com/posts/{postId}/comments',
                ],
                // (Optional) Used by HAL+JSON
                'curies' => [
                    'name' => 'example',
                    'href' => 'http://example.com/docs/rels/{rel}',
                ],
            ],
        ];
        new Mapper($mapping);
    }

    public function testItCanSetClassMap()
    {
        $mapper = new Mapper();
        $mapper->setClassMap([]);
        $this->assertEquals([], $mapper->getClassMap());
    }
}
