<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\RouterOS\Service;

use Dungap\Bridge\RouterOS\Contracts\HttpClientFactoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpClientFactory implements HttpClientFactoryInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        #[Autowire('%env(DUNGAP_ROUTEROS_URL)%')]
        private string $baseUrl,
        #[Autowire('%env(DUNGAP_ROUTEROS_USERNAME)%')]
        private string $username,
        #[Autowire('%env(DUNGAP_ROUTEROS_PASSWORD)%')]
        private string $password,
    ) {
    }

    public function create(): HttpCLientInterface
    {
        return $this->httpClient->withOptions([
            'base_uri' => $this->baseUrl,
            'auth_basic' => [$this->username, $this->password],
            'verify_host' => false,
            'verify_peer' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }
}
