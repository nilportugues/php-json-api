<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 17/01/16
 * Time: 1:57.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Domain\Contracts;

/**
 * Class ActionRepository.
 */
interface ActionRepository
{
    /**
     * @param $id
     *
     * @return mixed
     */
    public function find($id);

    /**
     * @param $id
     *
     * @return mixed
     */
    public function delete($id);

    /**
     * @param array $data
     * @param array $values
     * @param       $errorBag
     *
     * @return mixed
     */
    public function persist(array $data, array $values, $errorBag);

    /**
     * @return mixed
     */
    public function findBy();

    /**
     * @return mixed
     */
    public function count();
}
