<?php

namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Tests for customers api endpoints.
 */
class CustomersTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /**
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetCollection(): void
    {
        $options = [
            'headers' => [
                'accept' => 'application/json'
            ]
        ];
        $response = static::createClient()->request('GET', '/api/customers', $options);

        $this->assertResponseIsSuccessful();

        foreach ($response->toArray() as $row) {
            $this->assertArrayHasKey('fullName', $row);
            $this->assertArrayHasKey('email', $row);
            $this->assertArrayHasKey('country', $row);
            $this->assertArrayHasKey('id', $row);

            $this->assertArrayNotHasKey('username', $row);
            $this->assertArrayNotHasKey('gender', $row);
            $this->assertArrayNotHasKey('city', $row);
            $this->assertArrayNotHasKey('phone', $row);

            $this->assertArrayNotHasKey('any', $row);
        }
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function testGetItem(): void
    {
        $options = [
            'headers' => [
                'accept' => 'application/json'
            ]
        ];
        $response = static::createClient()->request('GET', '/api/customers', $options);

        $response = static::createClient()->request('GET', '/api/customers/' . $response->toArray()[0]['id'], $options);

        $row = $response->toArray();
        $this->assertArrayHasKey('fullName', $row);
        $this->assertArrayHasKey('email', $row);
        $this->assertArrayHasKey('country', $row);
        $this->assertArrayHasKey('id', $row);
        $this->assertArrayHasKey('username', $row);
        $this->assertArrayHasKey('gender', $row);
        $this->assertArrayHasKey('city', $row);
        $this->assertArrayHasKey('phone', $row);

        $this->assertArrayNotHasKey('any', $row);
    }
}