<?php

namespace NilPortugues\Api\Mapping;

use InvalidArgumentException;

class Mapping
{
    /**
     * @var string
     */
    private $classAlias = '';

    /**
     * @var array
     */
    private $aliasedProperties = [];
    /**
     * @var array
     */
    private $hiddenProperties = [];

    /**
     * @var array
     */
    private $idProperties = [];

    /**
     * @var array
     */
    private $relationships = [];

    /**
     * @var array
     */
    private $metaData = [];

    /**
     * @param       $className
     * @param null  $resourceUrlPattern
     * @param array $idProperties
     */
    public function __construct($className, $resourceUrlPattern = null, array $idProperties = [])
    {
        $this->className = (string) $className;
        $this->resourceUrlPattern = (string) $resourceUrlPattern;
        $this->idProperties = $idProperties;
    }

    /**
     * @param string $aliasedClass
     *
     * @return $this
     */
    public function setClassAlias($aliasedClass)
    {
        $this->classAlias = $aliasedClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getClassAlias()
    {
        return $this->classAlias;
    }

    /**
     * @return array
     */
    public function getIdProperties()
    {
        return $this->idProperties;
    }

    /**
     * @param array $idProperties
     */
    public function setIdProperties(array $idProperties)
    {
        $this->idProperties = array_merge($this->idProperties, $idProperties);
    }

    /**
     * @param $idProperty
     */
    public function addIdProperty($idProperty)
    {
        $this->idProperties[] = (string) $idProperty;
    }

    /**
     * @param string $resourceUrlPattern
     */
    public function setResourceUrlPattern($resourceUrlPattern)
    {
        $this->resourceUrlPattern = (string) $resourceUrlPattern;
    }

    /**
     * @param string $propertyName
     */
    public function hideProperty($propertyName)
    {
        $this->hiddenProperties[] = $propertyName;
    }

    /**
     * @param $propertyName
     * @param $propertyAlias
     */
    public function addPropertyAlias($propertyName, $propertyAlias)
    {
        $this->aliasedProperties[$propertyName] = $propertyAlias;
    }

    /**
     * @param array $properties
     */
    public function setPropertyNameAliases(array $properties)
    {
        $this->aliasedProperties = array_merge($this->aliasedProperties, $properties);
    }

    /**
     * @return mixed
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     */
    public function getResourceUrl()
    {
        return $this->resourceUrlPattern;
    }

    /**
     * @return array
     */
    public function getAliasedProperties()
    {
        return $this->aliasedProperties;
    }

    /**
     * @return array
     */
    public function getHiddenProperties()
    {
        return $this->hiddenProperties;
    }

    /**
     * @param array $hidden
     */
    public function setHiddenProperties(array $hidden)
    {
        $this->hiddenProperties = array_merge($this->hiddenProperties, array_values($hidden));
    }

    /**
     * @return array
     */
    public function getRelationships()
    {
        return $this->relationships;
    }

    /**
     * @param array $relationships
     */
    public function setRelationships(array $relationships)
    {
        $this->relationships = $relationships;
    }

    /**
     * @param $key
     * @param $value
     */
    public function addRelationship($key, $value)
    {
        $this->relationships[$key] = $value;
    }
    /**
     * @return array
     */
    public function getMetaData()
    {
        return $this->metaData;
    }

    /**
     * @param array $metaData
     */
    public function setMetaData(array $metaData)
    {
        $this->metaData = $metaData;
    }

    /**
     * @param string $key
     * @param $value
     */
    public function addMetaData($key, $value)
    {
        $this->metaData[$key] = $value;
    }
}
