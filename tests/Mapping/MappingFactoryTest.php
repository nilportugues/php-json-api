<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/27/15
 * Time: 8:33 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Tests\Api\Mapping;

use NilPortugues\Api\Mapping\MappingException;
use NilPortugues\Api\Mapping\MappingFactory;
use NilPortugues\Tests\Api\Dummy\ComplexObject\Post;

class MappingFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testItCanBuildMappingsFromArray()
    {
        $mappedClass = [
            'class' => Post::class,
            'alias' => 'Message',
            'aliased_properties' => [
                'title' => 'headline',
                'content' => 'body',
            ],
            'hide_properties' => [
                'comments',
            ],
            'id_properties' => [
                'postId',
            ],
            'urls' => [
                'self' => 'http://example.com/posts/{postId}',
                'related' => 'http://example.com/posts/{postId}/author',
                'relationships' => [
                    'self' => 'http://example.com/posts/{postId}/relationships/author',
                    'tags' => 'http://example.com/posts/{postId}/relationships/tags',
                ],
            ],
        ];

        $mapping = MappingFactory::fromArray($mappedClass);

        $this->assertEquals(Post::class, $mapping->getClassName());
        $this->assertEquals('message', $mapping->getClassAlias());
        $this->assertEquals(['title' => 'headline', 'content' => 'body'], $mapping->getAliasedProperties());
        $this->assertEquals(['comments'], $mapping->getHiddenProperties());
        $this->assertEquals(['postId'], $mapping->getIdProperties());
        $this->assertEquals('http://example.com/posts/{postId}', $mapping->getResourceUrl());
        $this->assertEquals('http://example.com/posts/{postId}/author', $mapping->getRelatedUrl());
        $this->assertEquals('http://example.com/posts/{postId}/relationships/author', $mapping->getRelationshipSelfUrl());
    }

    public function testItWillThrowExceptionIfArrayHasNoClassKey()
    {
        $this->setExpectedException(MappingException::class);
        $mappedClass = [];
        MappingFactory::fromArray($mappedClass);
    }

    public function testItWillThrowExceptionIfArrayHasNoIdPropertiesKey()
    {
        $this->setExpectedException(MappingException::class);
        $mappedClass = ['class' => 'Post', 'id_properties' => [], 'urls' => ['self' => 'some/url']];
        MappingFactory::fromArray($mappedClass);
    }

    public function testItWillThrowExceptionIfArrayHasNoSelfUrlKey()
    {
        $this->setExpectedException(MappingException::class);
        $mappedClass = ['class' => 'Post', 'id_properties' => ['postId'], 'urls' => []];
        MappingFactory::fromArray($mappedClass);
    }
}
