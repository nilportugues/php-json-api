<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 17/01/16
 * Time: 2:40.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Infrastructure\Repositories\Actions;

use NilPortugues\Api\JsonApi\Infrastructure\Repositories\Actions\Contracts\ResourceRepositoryDriver;
use NilPortugues\Api\JsonApi\Domain\Model\Contracts\ResourceRepository as ResourceRepositoryInterface;
use NilPortugues\Api\JsonApi\Server\Data\ResourceNotFoundException;

/**
 * Class ResourceRepository.
 */
class ResourceRepository implements ResourceRepositoryInterface
{
    /**
     * @var ResourceRepositoryDriver
     */
    private $repository;

    /**
     * ResourceRepository constructor.
     *
     * @param ResourceRepositoryDriver $repository
     */
    public function __construct(ResourceRepositoryDriver $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $id
     *
     * @return mixed
     *
     * @throws ResourceNotFoundException
     */
    public function find($id)
    {
        $resource = $this->repository->find($id);

        if (empty($resource)) {
            throw new ResourceNotFoundException();
        }

        return $resource;
    }

    /**
     * @param $id
     *
     * @return mixed
     *
     * @throws ResourceNotFoundException
     */
    public function delete($id)
    {
        $this->find($id);

        return $this->repository->delete($id);
    }

    /**
     * @param array $data
     * @param array $values
     *
     * @return mixed
     */
    public function persist(array $data, array $values)
    {
    }

    /**
     * @return mixed
     */
    public function findBy()
    {
    }

    /**
     * @return mixed
     */
    public function count()
    {
    }
}
