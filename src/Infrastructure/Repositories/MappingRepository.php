<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 17/01/16
 * Time: 2:08.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Server\Actions;

use NilPortugues\Api\JsonApi\Domain\Model\Contracts\MappingRepository as MappingRepositoryInterface;
use NilPortugues\Api\Transformer\Transformer;

/**
 * Class MappingRepository.
 */
class MappingRepository implements MappingRepositoryInterface
{
    /**
     * @var Transformer
     */
    private $mappings;

    /**
     * MappingRepository constructor.
     *
     * @param Transformer $transformer
     */
    public function __construct(Transformer $transformer)
    {
        $this->mappings = $transformer;
    }

    /**
     * @param string $alias
     *
     * @return \NilPortugues\Api\Mapping\Mapping
     */
    public function findByAlias($alias)
    {
        return $this->mappings->getMappingByAlias($alias);
    }

    /**
     * @param string $className
     *
     * @return \NilPortugues\Api\Mapping\Mapping
     */
    public function findByClassName($className)
    {
        return $this->mappings->getMappingByClassName($className);
    }
}
