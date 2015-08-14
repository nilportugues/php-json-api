<?php

namespace NilPortugues\Api\Mapping;

use NilPortugues\Api\Transformer\Helpers\RecursiveFormatterHelper;
use NilPortugues\Api\Transformer\Json\Helpers\JsonApi\PropertyHelper;

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
     * @var string
     */
    private $selfUrl = '';

    /**
     * @var array
     */
    private $otherUrls = [];

    /**
     * @var array
     */
    private $relationshipSelfUrl = [];

    /**
     * @var array
     */
    private $filterKeys = [];

    /**
     * @var array
     */
    private $curies = [];

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
     * @return string
     */
    public function getClassAlias()
    {
        return $this->classAlias;
    }

    /**
     * @param string $aliasedClass
     *
     * @return $this
     */
    public function setClassAlias($aliasedClass)
    {
        $this->classAlias = RecursiveFormatterHelper::camelCaseToUnderscore(
            PropertyHelper::namespaceAsArrayKey($aliasedClass)
        );

        return $this;
    }

    /**
     * @return array
     */
    public function getIdProperties()
    {
        return (array) $this->idProperties;
    }

    /**
     * @param $idProperty
     */
    public function addIdProperty($idProperty)
    {
        $this->idProperties[] = (string) $idProperty;
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

        $this->updatePropertyMappings($propertyName, $propertyAlias);
    }

    /**
     * @param $propertyName
     * @param $propertyAlias
     */
    private function updatePropertyMappings($propertyName, $propertyAlias)
    {
        if (in_array($propertyName, $this->idProperties)) {
            $position = array_search($propertyName, $this->idProperties, true);
            $this->idProperties[$position] = $propertyAlias;
        }

        $search = sprintf('{%s}', $propertyName);
        $replace = sprintf('{%s}', $propertyAlias);

        $this->selfUrl = str_replace($search, $replace, $this->selfUrl);
        $this->resourceUrlPattern = str_replace($search, $replace, $this->resourceUrlPattern);
        $this->otherUrls = str_replace($search, $replace, $this->otherUrls);
    }

    /**
     * @param array $properties
     */
    public function setPropertyNameAliases(array $properties)
    {
        $this->aliasedProperties = array_merge($this->aliasedProperties, $properties);

        foreach ($this->aliasedProperties as $propertyName => $propertyAlias) {
            $this->updatePropertyMappings($propertyName, $propertyAlias);
        }
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
        return (array) $this->hiddenProperties;
    }

    /**
     * @param array $hidden
     */
    public function setHiddenProperties(array $hidden)
    {
        $this->hiddenProperties = array_merge($this->hiddenProperties, $hidden);
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
    public function addAdditionalRelationships(array $relationships)
    {
        $this->relationships = $relationships;
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
     * @param        $value
     */
    public function addMetaData($key, $value)
    {
        $this->metaData[$key] = $value;
    }

    /**
     * @return string
     */
    public function getSelfUrl()
    {
        return $this->selfUrl;
    }

    /**
     * @param string $self
     *
     * @throws \InvalidArgumentException
     */
    public function setSelfUrl($self)
    {
        $this->selfUrl = (string) $self;
    }

    /**
     * @param $propertyName
     *
     * @return string
     */
    public function getRelatedUrl($propertyName)
    {
        return (!empty($this->relationshipSelfUrl[$propertyName]['related']))
            ? $this->relationshipSelfUrl[$propertyName]['related']
            : '';
    }

    /**
     * @param array $filterKeys
     */
    public function setFilterKeys(array $filterKeys)
    {
        $this->filterKeys = $filterKeys;
    }

    /**
     * @return array
     */
    public function getFilterKeys()
    {
        return (array) $this->filterKeys;
    }

    /**
     * @param string $propertyName
     * @param string $urls
     *
     * @return $this
     */
    public function setRelationshipUrls($propertyName, $urls)
    {
        $this->relationshipSelfUrl[$propertyName] = $urls;

        return $this;
    }

    /**
     * @param $propertyName
     *
     * @return string
     */
    public function getRelationshipSelfUrl($propertyName)
    {
        return (!empty($this->relationshipSelfUrl[$propertyName]['self']))
            ? $this->relationshipSelfUrl[$propertyName]['self']
            : '';
    }

    /**
     * @param array $urls
     */
    public function setUrls(array $urls)
    {
        $this->otherUrls = $urls;
    }

    /**
     * @return array
     */
    public function getUrls()
    {
        return $this->otherUrls;
    }

    /**
     * @param array $curies
     *
     * @throws MappingException
     */
    public function setCuries(array $curies)
    {
        if (empty($curies['name']) || empty($curies['href'])) {
            throw new MappingException('Curies must define "name" and "href" properties');
        }

        $this->curies = $curies;
    }

    public function getCuries()
    {
        return $this->curies;
    }
}
