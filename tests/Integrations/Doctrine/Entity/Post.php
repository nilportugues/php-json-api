<?php

namespace NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity;

/**
 * Post.
 */
class Post
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity\Customer
     */
    private $customer;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Post
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Post
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set customer.
     *
     * @param \NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity\Customer $customer
     *
     * @return Post
     */
    public function setCustomer(\NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer.
     *
     * @return \NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }
}
