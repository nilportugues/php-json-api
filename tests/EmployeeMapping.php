<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 17/01/16
 * Time: 18:27.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi;

/**
 * Class EmployeeMapping.
 */
class EmployeeMapping
{
    public static function mapping()
    {
        return [
            'alias' => 'worker',
            'class' => Employee::class,
            'aliased_properties' => [
                'firstName' => 'name',
                'surname' => 'family_name',
            ],
            'hide_properties' => [

            ],
            'id_properties' => [
                'id',
            ],
            'urls' => [
                'self' => 'http://example.com/employee/{id}',
            ],
        ];
    }
}
