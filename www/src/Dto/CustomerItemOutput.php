<?php

namespace App\Dto;

use App\Entity\Customer;

/**
 * Output DTO for Customer entity
 */
class CustomerItemOutput extends CustomerOutput
{
    public string $username;

    public string $gender;

    public string $city;

    public string $phone;

    /**
     * @param Customer $object
     *
     * @return $this
     */
    public static function createFromObject(Customer $object): self
    {
        $instance = parent::createFromObject($object);

        $instance->username = $object->getUsername() ?? '';
        $instance->gender = $object->getGender() ?? '';
        $instance->city = $object->getCity() ?? '';
        $instance->phone = $object->getPhone() ?? '';

        return $instance;
    }
}