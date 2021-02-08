<?php

namespace App\Service\UserProvider;

interface UserProviderInterface
{
    /**
     * @return UserDTO[]
     */
    public function getUsers(): array;
}