<?php

namespace App\Service\UserProvider;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

/**
 * Gets users from randomuser.me service
 */
class RandomUserFromAPIProvider implements UserProviderInterface
{
    private string $filterNationality = 'AU';

    private int $resultsCount = 100;

    private HttpClientInterface $client;

    private string $api = 'https://randomuser.me/api';

    /**
     * RandomUserFromAPIProvider constructor.
     *
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /** @inheritdoc */
    public function getUsers(): array
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->getAPIParams()
            );
        } catch (TransportExceptionInterface $e) {
            return  [];
        } catch (Throwable $e) {
            return  [];
        }

        // ...

        try {
            $content = $response->toArray();
        } catch (ClientExceptionInterface $e) {
            return  [];
        } catch (RedirectionExceptionInterface $e) {
            return  [];
        } catch (ServerExceptionInterface $e) {
            return  [];
        } catch (TransportExceptionInterface $e) {
            return  [];
        } catch (DecodingExceptionInterface $e) {
            return  [];
        }

        $results = [];
        if ($content['results']) {
            foreach ($content['results'] as $userData) {
                $results[] = UserDTO::createFromArray([
                    'firstName' => $userData['name']['first'],
                    'lastName' => $userData['name']['last'],
                    'email' => $userData['email'],
                    'country' => $userData['location']['country'],
                    'username' => $userData['login']['username'],
                    'gender' => $userData['gender'],
                    'city' => $userData['location']['city'],
                    'phone' => $userData['phone']
                ]);
            }
        }

        return $results;
    }

    /**
     * @return string
     */
    private function getAPIParams(): string
    {
        return sprintf('%s?nat=%s&results=%s', $this->api, $this->filterNationality, $this->resultsCount);
    }
}