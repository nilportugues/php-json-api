<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 17/01/16
 * Time: 2:43.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Infrastructure\Repositories\Resource\Drivers;

use NilPortugues\Api\JsonApi\Infrastructure\Repositories\Resource\Contracts\ResourceRepositoryDriver;

/**
 * Class InMemoryResourceRepository.
 */
class InMemoryResourceRepository implements ResourceRepositoryDriver
{
    /**
     * @var array
     */
    private $data;

    /**
     * InMemoryResourceRepository constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function delete($id)
    {
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
     * @return array
     */
    public function findBy()
    {
    }

    /**
     * @return int
     */
    public function count()
    {
    }
}
