<?php

namespace NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Mappings;

use NilPortugues\Api\Mappings\JsonApiMapping;
use NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity\Post;

class PostMapping implements JsonApiMapping
{
    /**
     * {@inhertidoc}.
     */
    public function getClass()
    {
        return Post::class;
    }
    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return '';
    }
    /**
     * {@inheritdoc}
     */
    public function getAliasedProperties()
    {
        return [];
    }
    /**
     * {@inheritdoc}
     */
    public function getHideProperties()
    {
        return [];
    }
    /**
     * {@inheritdoc}
     */
    public function getIdProperties()
    {
        return [
            'id',
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function getUrls()
    {
        return [
            'self' => 'http://example.com/post/{id}',

        ];
    }
    /**
     * {@inheritdoc}
     */
    public function getRelationships()
    {
        return [];
    }
}
