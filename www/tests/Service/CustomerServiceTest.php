<?php

namespace App\Tests\Service;

use App\Entity\Customer;
use App\Service\CustomerService;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Tests for @see CustomerService
 */
class CustomerServiceTest extends KernelTestCase
{
    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $this->truncateEntities([
            Customer::class,
        ]);
    }

    /**
     * @param array $entities
     *
     * @throws Exception
     */
    private function truncateEntities(array $entities): void
    {
        $connection = $this->getEntityManager()->getConnection();
        $databasePlatform = $connection->getDatabasePlatform();
        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->executeQuery('SET session_replication_role = \'replica\'');
        }
        foreach ($entities as $entity) {
            $query = $databasePlatform->getTruncateTableSQL(
                $this->getEntityManager()->getClassMetadata($entity)->getTableName()
            );
            $connection->executeStatement($query);
        }
        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->executeQuery('SET session_replication_role = \'origin\'');
        }
    }

    /**
     * @return EntityManager
     */
    private function getEntityManager(): EntityManager
    {
        return self::$kernel->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @return CustomerService
     */
    private function getCustomerService(): CustomerService
    {
        /** @var CustomerService $customerService */
        $customerService = self::$container->get(CustomerService::class);

        return $customerService;
    }

    /**
     * @covers \App\Service\CustomerService::importUsersToDatabase
     * @throws Exception
     */
    public function testImportUsersToDatabaseMinCount(): void
    {
        $minCountExpected = [
            1 => 2,
            2 => 2,
            3 => 4,
            4 => 4,
            5 => 6,
            6 => 6,
            7 => 8,
            8 => 8,
            9 => 8,
            10 => 8,
            20 => 8,
            1000 => 8
        ];

        foreach ($minCountExpected as $minCount => $expected) {
            $this->getCustomerService()->importUsersToDatabase($minCount);

            $actualEntities = $this->getEntityManager()->getRepository(Customer::class)->findAll();
            $this->assertCount($expected, $actualEntities, 'Case with $minCount=' . $minCount . ' is wrong.');

            $this->truncateEntities([Customer::class]);
        }

    }
}