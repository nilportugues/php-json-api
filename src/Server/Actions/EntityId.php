<?php

namespace NilPortugues\Api\JsonApi\Server\Actions;

use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Identity;

class EntityId implements Identity
{
    protected $id;

    /**
     * EntityId constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function id()
    {
         return $this->id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->id;
    }
}

