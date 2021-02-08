<?php

namespace App\Service\UserProvider;

/**
 * Mocked user provider for unit tests.
 */
class TestingUserProvider implements UserProviderInterface
{

    /** @inheritdoc */
    public function getUsers(): array
    {
        $data = [
            [
                'firstName' => 'Any',
                'lastName' => 'Name',
                'email' => 'random@mail.com',
                'country' => 'AUS',
                'username' => 'hello_user_2000',
                'gender' => 'male',
                'city' => 'Kazan',
                'phone' => '123242342'
            ],
            [
                'firstName' => 'Other',
                'lastName' => 'Man',
                'email' => 'random22@mail.com',
                'country' => 'RU',
                'username' => 'user_name',
                'gender' => 'female',
                'city' => 'Moscow',
                'phone' => '98865432'
            ]
        ];

        $result = [];
        foreach ($data as $datum) {
            $result[] = UserDTO::createFromArray($datum);
        }

        return $result;
    }
}