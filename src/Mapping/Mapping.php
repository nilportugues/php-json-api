<?php

namespace NilPortugues\Api\Mapping;

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
    private $firstUrl = '';
    /**
     * @var string
     */
    private $lastUrl = '';
    /**
     * @var string
     */
    private $prevUrl = '';
    /**
     * @var string
     */
    private $nextUrl = '';
    /**
     * @var string
     */
    private $selfUrl = '';

    /**
     * @var string
     */
    private $relatedUrl = '';

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
        $this->classAlias = $aliasedClass;

        return $this;
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

        $this->firstUrl = str_replace($search, $replace, $this->firstUrl);
        $this->lastUrl = str_replace($search, $replace, $this->lastUrl);
        $this->prevUrl = str_replace($search, $replace, $this->prevUrl);
        $this->nextUrl = str_replace($search, $replace, $this->nextUrl);
        $this->selfUrl = str_replace($search, $replace, $this->selfUrl);
        $this->relatedUrl = str_replace($search, $replace, $this->relatedUrl);
        $this->resourceUrlPattern = str_replace($search, $replace, $this->resourceUrlPattern);
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
        return $this->hiddenProperties;
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
     * @return string
     */
    public function getPrevUrl()
    {
        return $this->prevUrl;
    }

    /**
     * @param string $prevUrl
     *
     * @throws \InvalidArgumentException
     */
    public function setPrevUrl($prevUrl)
    {
        $this->prevUrl = (string) $prevUrl;
    }

    /**
     * @return string
     */
    public function getNextUrl()
    {
        return $this->nextUrl;
    }

    /**
     * @param string $nextUrl
     *
     * @throws \InvalidArgumentException
     */
    public function setNextUrl($nextUrl)
    {
        $this->nextUrl = (string) $nextUrl;
    }

    /**
     * @return string
     */
    public function getLastUrl()
    {
        return $this->lastUrl;
    }

    /**
     * @param string $lastUrl
     *
     * @throws \InvalidArgumentException
     */
    public function setLastUrl($lastUrl)
    {
        $this->lastUrl = (string) $lastUrl;
    }

    /**
     * @return string
     */
    public function getFirstUrl()
    {
        return $this->firstUrl;
    }

    /**
     * @param string $firstUrl
     *
     * @throws \InvalidArgumentException
     */
    public function setFirstUrl($firstUrl)
    {
        $this->firstUrl = (string) $firstUrl;
    }

    /**
     * @param string $relatedUrl
     *
     * @return $this
     */
    public function setRelatedUrl($relatedUrl)
    {
        $this->relatedUrl = (string) $relatedUrl;
    }

    /**
     * @return string
     */
    public function getRelatedUrl()
    {
        return $this->relatedUrl;
    }
}
