<?php

namespace App\Service;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Service\UserProvider\UserProviderInterface;
use Doctrine\ORM\ORMException;

/**
 * Working with customers
 */
class CustomerService
{
    private UserProviderInterface $userProvider;

    private CustomerRepository $customerRepository;

    private int $allowedNumberOfAttempts = 3;

    /**
     * CustomerService constructor.
     *
     * @param UserProviderInterface $userProvider
     * @param CustomerRepository $customerRepository
     * @param int|null $allowedNumberOfAttempts
     */
    public function __construct(UserProviderInterface $userProvider, CustomerRepository $customerRepository, ?int $allowedNumberOfAttempts = null)
    {
        $this->userProvider = $userProvider;
        $this->customerRepository = $customerRepository;

        if ($allowedNumberOfAttempts) {
            $this->allowedNumberOfAttempts = $allowedNumberOfAttempts;
        }
    }

    /**
     * @param int $minCount
     *
     * @return void
     */
    public function importUsersToDatabase(int $minCount): void
    {
        $finished = false;
        $tryNumber = 0;
        while (!$finished) {
            $tryNumber += 1;
            foreach ($this->userProvider->getUsers() as $userDTO) {
                $customer = new Customer(
                    $userDTO->getFirstName(),
                    $userDTO->getLastName(),
                    $userDTO->getEmail(),
                    $userDTO->getCountry(),
                    $userDTO->getUsername(),
                    $userDTO->getGender(),
                    $userDTO->getCity(),
                    $userDTO->getPhone()
                );

                try {
                    $this->customerRepository->addToQueue($customer);
                } catch (ORMException $e) {
                    // ...
                }
            }

            try {
                $this->customerRepository->saveQueue();
            } catch (ORMException $e) {
                // ...
            }

            $finished = count($this->customerRepository->findAll()) >= $minCount || $tryNumber >= $this->getAllowedNumberOfAttempts();
        }
    }

    /**
     * @return int
     */
    protected function getAllowedNumberOfAttempts(): int
    {
        return $this->allowedNumberOfAttempts;
    }
}