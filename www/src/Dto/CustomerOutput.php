<?php

namespace App\Dto;

use App\Entity\Customer;

/**
 * Base class for Output DTO for Customer entity
 */
abstract class CustomerOutput
{
    /**
     * @var string User`s full name
     */
    public string $fullName;

    /**
     * @var string Email
     */
    public string $email;

    /**
     * @var string Country from
     */
    public string $country;

    /**
     * @param Customer $object
     *
     * @return $this
     */
    static public function createFromObject(Customer $object): self
    {
        $instance = new static();

        $instance->fullName = $object->getFullName();
        $instance->email = $object->getEmail() ?? '';
        $instance->country = $object->getCountry() ?? '';

        return $instance;
    }
}