<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/27/15
 * Time: 9:58 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Server\Data;

use NilPortugues\Api\JsonApi\Domain\Contracts\MappingRepository;
use NilPortugues\Api\JsonApi\JsonApiTransformer;

class AttributeNameResolverService
{
    /**
     * @var MappingRepository
     */
    private $mappingRepository;

    /**
     * AttributeNameResolverService constructor.
     *
     * @param MappingRepository $mappingRepository
     */
    public function __construct(MappingRepository $mappingRepository)
    {
        $this->mappingRepository = $mappingRepository;
    }

    /**
     * Returns attributes using its real name, instead of the one provided by the mapping.
     *
     * @param array $data
     *
     * @return array
     */
    public function resolve(array $data)
    {
        $mapping = $this->mappingRepository->findByAlias($data[JsonApiTransformer::TYPE_KEY]);
        $aliases = $mapping->getAliasedProperties();

        $keys = str_replace(
            array_values($aliases),
            array_keys($aliases),
            array_keys($data[JsonApiTransformer::ATTRIBUTES_KEY])
        );

        return array_combine($keys, array_values($data[JsonApiTransformer::ATTRIBUTES_KEY]));
    }
}
