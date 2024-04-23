<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\RouterOS;

use Dungap\Bridge\RouterOS\Contracts\RequestInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class Request implements RequestInterface
{
    public function __construct(
        #[Autowire('%env(ROUTEROS_BASE_URL)%')]
        private string $baseUrl,
        #[Autowire('%env(ROUTEROS_USERNAME)%')]
        private string $username,
        #[Autowire('%env(ROUTEROS_PASSWORD)%')]
        private string $password,

        private HttpClientInterface $httpClient
    ) {
    }

    public function request(string $method, string $path): array
    {
        $client = $this->httpClient;
        $path = '/rest'.$path;
        $options = [
            'base_uri' => $this->baseUrl,
            'auth_basic' => [$this->username, $this->password],
            'verify_host' => false,
            'verify_peer' => false,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ];

        $response = $client->request($method, $path, $options);

        return $response->toArray(true);
    }
}
