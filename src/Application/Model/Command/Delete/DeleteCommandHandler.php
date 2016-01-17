<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/2/15
 * Time: 9:38 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Application\Command\Delete;

use NilPortugues\Api\JsonApi\Domain\Model\Contracts\ResourceRepository;
use NilPortugues\Api\JsonApi\Domain\Model\Contracts\MappingRepository;

class DeleteCommandHandler
{
    /**
     * @var ResourceRepository
     */
    protected $resourceRepository;
    /**
     * @var MappingRepository
     */
    protected $mappingRepository;

    /**
     * DeleteResourceHandler constructor.
     *
     * @param MappingRepository  $mappingRepository
     * @param ResourceRepository $resourceRepository
     */
    public function __construct(MappingRepository $mappingRepository, ResourceRepository $resourceRepository)
    {
        $this->mappingRepository = $mappingRepository;
        $this->resourceRepository = $resourceRepository;
    }

    /**
     * @param DeleteCommand $resource
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(DeleteCommand $resource)
    {
        $this->resourceRepository->find($resource->id());

        $this->resourceRepository->delete($resource->id());
    }
}
