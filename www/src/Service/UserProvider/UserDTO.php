<?php

namespace App\Service\UserProvider;

/**
 * Class UserDTO
 */
class UserDTO
{
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $country;
    private string $username;
    private string $gender;
    private string $city;
    private string $phone;

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Create a UserDTO object from array
     *
     * @param array $data
     *
     * @return static
     */
    public static function createFromArray(array $data): self
    {
        $instance = new self();

        // TODO: add more validations and processing
        $instance->firstName = $data['firstName'];
        $instance->lastName = $data['lastName'];
        $instance->email = $data['email'];
        $instance->country = $data['country'];
        $instance->username = $data['username'];
        $instance->gender = $data['gender'];
        $instance->city = $data['city'];
        $instance->phone = $data['phone'];

        return $instance;
    }
}