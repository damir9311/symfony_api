<?php

namespace App\Dto;

use App\Entity\Customer;

/**
 * Output DTO for Customer entity in the the list
 */
class CustomerListItemOutput extends CustomerOutput
{
    public int $id;

    /** @inheritDoc */
    public static function createFromObject(Customer $object): self
    {
        $instance = parent::createFromObject($object);

        $instance->id = $object->getId() ?? '';

        return $instance;
    }
}