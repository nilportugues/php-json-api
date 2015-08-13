<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/26/15
 * Time: 12:44 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\Mapping;

/**
 * Class Mapper.
 */
class Mapper
{
    /**
     * @var array
     */
    private $classMap = [];
    /**
     * @var array
     */
    private $aliasMap = [];

    /**
     * @param array $mappings
     *
     * @throws MappingException
     */
    public function __construct(array &$mappings = null)
    {
        if (is_array($mappings)) {
            foreach ($mappings as $mappedClass) {
                $mapping = MappingFactory::fromArray($mappedClass);

                if (false === empty($this->aliasMap[$mapping->getClassAlias()])) {
                    throw new MappingException(
                        'Class with the same name already present. Please add an alias or change an existing one.'
                    );
                }

                $this->classMap[$mapping->getClassName()] = $mapping;
                $this->aliasMap[$mapping->getClassAlias()] = $mapping->getClassName();
            }
        }
    }

    /**
     * @param array $array
     */
    public function setClassMap(array $array)
    {
        $this->classMap = $array;
    }

    /**
     * @return array
     */
    public function getClassMap()
    {
        return $this->classMap;
    }

    /**
     * @return array
     */
    public function getAliasMap()
    {
        return $this->aliasMap;
    }

    /**
     * @param string $alias
     *
     * @return string
     *
     * @throws MappingException
     */
    public function getClassFromAlias($alias)
    {
        if (empty($this->aliasMap[$alias])) {
            throw new MappingException('Alias not found in alias map');
        }

        return $this->aliasMap[$alias];
    }
}
