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

use NilPortugues\Api\JsonApi\Infrastructure\Repositories\Actions\Contracts\ActionRepositoryDriver;
use NilPortugues\Api\JsonApi\Server\Contracts\ActionRepository as ActionRepositoryInterface;

/**
 * Class ActionRepository.
 */
class ActionRepository implements ActionRepositoryInterface
{
    /**
     * @var ActionRepositoryDriver
     */
    private $repository;

    /**
     * ActionRepository constructor.
     *
     * @param ActionRepositoryDriver $repository
     */
    public function __construct(ActionRepositoryDriver $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    /**
     * @param array $data
     * @param array $values
     * @param       $errorBag
     *
     * @return mixed
     */
    public function persist(array $data, array $values, $errorBag)
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
