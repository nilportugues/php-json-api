<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/26/15
 * Time: 12:11 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\Mapping;

/**
 * Class MappingFactory.
 */
class MappingFactory
{
    /**
     * @param array $mappedClass
     *
     * @return Mapping
     */
    public static function fromArray(array &$mappedClass)
    {
        $className = self::getClass($mappedClass);
        $resourceUrl = self::getSelfUrl($mappedClass);
        $idProperties = self::getIdProperties($mappedClass);

        $mapping = new Mapping($className, $resourceUrl, $idProperties);

        $mapping->setClassAlias((empty($mappedClass['alias'])) ? $className : $mappedClass['alias']);

        if (false === empty($mappedClass['aliased_properties'])) {
            $mapping->setPropertyNameAliases($mappedClass['aliased_properties']);
        }

        if (false === empty($mappedClass['hide_properties'])) {
            $mapping->setHiddenProperties($mappedClass['hide_properties']);
        }

        if (false === empty($mappedClass['urls']['related'])) {
            $mapping->setRelatedUrl($mappedClass['urls']['related']);
            unset($mappedClass['urls']['related']);
        }

        if (false === empty($mappedClass['urls']['relationships']['self'])) {
            $mapping->setRelationshipUrl($mappedClass['urls']['relationships']['self']);
            unset($mappedClass['urls']['relationships']['self']);
        }

        if (false === empty($mappedClass['urls']['relationships'])) {
            $mapping->addAdditionalRelationships($mappedClass['urls']['relationships']);
        }

        return $mapping;
    }

    /**
     * @param array $mappedClass
     *
     * @throws MappingException
     *
     * @return mixed
     */
    private static function getClass(array &$mappedClass)
    {
        if (empty($mappedClass['class'])) {
            throw new MappingException(
                'Could not find "class" property. This is required for class to be mapped'
            );
        }

        return $mappedClass['class'];
    }

    /**
     * @param array $mappedClass
     *
     * @throws MappingException
     *
     * @return mixed
     */
    private static function getSelfUrl(array &$mappedClass)
    {
        if (empty($mappedClass['urls']['self'])) {
            throw new MappingException(
                'Could not find "self" property under "urls". This is required in order to make the resource to be reachable.'
            );
        }

        return $mappedClass['urls']['self'];
    }

    /**
     * @param array $mappedClass
     *
     * @throws MappingException
     *
     * @return mixed
     */
    private static function getIdProperties(array &$mappedClass)
    {
        if (empty($mappedClass['id_properties'])) {
            throw new MappingException(
                'Could not find "id_properties" property with data . This is required in order to make the resource to be reachable.'
            );
        }

        return $mappedClass['id_properties'];
    }
}
