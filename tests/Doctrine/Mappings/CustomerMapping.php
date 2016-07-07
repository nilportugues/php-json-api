<?php
namespace NilPortugues\Tests\Api\JsonApi\Doctrine\Mappings;

use NilPortugues\Api\Mappings\JsonApiMapping;

class CustomerMapping implements JsonApiMapping
{
    /**
     * {@inhertidoc}
     */
    public function getClass() 
    {
        return \Doctrine\Entity\Customer::class;
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
    public function getAliasedProperties() {
        return [];
    }
    /**
     * {@inheritdoc}
     */
    public function getHideProperties(){
        return [];
    }
    /**
     * {@inheritdoc}
     */
    public function getIdProperties(){
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
            'self' => 'http://example.com/customer/{id}',
            
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