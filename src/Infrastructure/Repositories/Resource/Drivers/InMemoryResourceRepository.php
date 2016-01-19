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

use InvalidArgumentException;
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
     * @var string
     */
    private $idField;

    /**
     * InMemoryResourceRepository constructor.
     *
     * @param array  $data
     * @param string $idField
     */
    public function __construct(array $data = [], $idField = 'id')
    {
        $this->data = $data;
        $this->idField = $idField;
    }

    /**
     * @param $id
     *
     * @return mixed|null
     */
    public function find($id)
    {
        return (array_key_exists($id, $this->data)) ? $this->data[$id] : null;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        if (array_key_exists($id, $this->data)) {
            unset($this->data[$id]);
        }
    }

    /**
     * @param array $values
     *
     * @return mixed
     */
    public function persist(array $values)
    {
        if (false === array_key_exists($this->idField, $values)) {
           throw new InvalidArgumentException(
               sprintf("Could not get value for '%s' field.", $this->idField)
           );
        }

        $id = $values[$this->idField];
        $this->data[$id] = $values;
        return $values;
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
