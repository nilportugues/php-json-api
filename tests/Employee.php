<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 17/01/16
 * Time: 18:19.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi;

/**
 * Class Employee.
 */
class Employee
{
    private $id;
    private $firstName;
    private $company;
    private $emailAddress;
    private $surname;
    private $city;
    private $jobTitle;

    /**
     * Employee constructor.
     *
     * @param $id
     * @param $firstName
     * @param $company
     * @param $emailAddress
     * @param $surname
     * @param $city
     * @param $jobTitle
     */
    public function __construct($id, $firstName, $company, $emailAddress, $surname, $city, $jobTitle)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->company = $company;
        $this->emailAddress = $emailAddress;
        $this->surname = $surname;
        $this->city = $city;
        $this->jobTitle = $jobTitle;
    }

    /**
     * Returns value for `id`.
     *
     * @return mixed
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Returns value for `jobTitle`.
     *
     * @return mixed
     */
    public function jobTitle()
    {
        return $this->jobTitle;
    }

    /**
     * Returns value for `city`.
     *
     * @return mixed
     */
    public function city()
    {
        return $this->city;
    }

    /**
     * Returns value for `surname`.
     *
     * @return mixed
     */
    public function surname()
    {
        return $this->surname;
    }

    /**
     * Returns value for `emailAddress`.
     *
     * @return mixed
     */
    public function emailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * Returns value for `company`.
     *
     * @return mixed
     */
    public function company()
    {
        return $this->company;
    }

    /**
     * Returns value for `firstName`.
     *
     * @return mixed
     */
    public function firstName()
    {
        return $this->firstName;
    }
}
