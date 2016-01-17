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
    protected $actionRepository;
    /**
     * @var MappingRepository
     */
    protected $mappingRepository;

    /**
     * DeleteResourceHandler constructor.
     *
     * @param MappingRepository  $mappingRepository
     * @param ResourceRepository $actionRepository
     */
    public function __construct(MappingRepository $mappingRepository, ResourceRepository $actionRepository)
    {
        $this->mappingRepository = $mappingRepository;
        $this->actionRepository = $actionRepository;
    }

    /**
     * @param DeleteCommand $resource
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(DeleteCommand $resource)
    {
        $this->actionRepository->find($resource->id());

        $this->actionRepository->delete($resource->id());
    }
}
