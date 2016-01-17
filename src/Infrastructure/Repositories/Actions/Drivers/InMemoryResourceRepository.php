<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 17/01/16
 * Time: 2:43.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Infrastructure\Repositories\Actions\Drivers;

use NilPortugues\Api\JsonApi\Infrastructure\Repositories\Actions\Contracts\ResourceRepositoryDriver;

/**
 * Class InMemoryResourceRepository.
 */
class InMemoryResourceRepository implements ResourceRepositoryDriver
{
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
     * @param       $errorBag
     *
     * @return mixed
     */
    public function persist(array $data, array $values, $errorBag)
    {
    }

    public function findBy()
    {
    }

    public function count()
    {
    }
}
