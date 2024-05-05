<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\RouterOS\Service;

use Dungap\Bridge\RouterOS\Service\HttpClientFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpClientFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $client = $this->createMock(HttpClientInterface::class);
        $factory = new HttpClientFactory(
            $client,
            'https://localhost/rest/url',
            'admin',
            'admin'
        );

        $client->expects($this->once())
            ->method('withOptions')
            ->willReturn($client)
        ;

        $factory->create();
    }
}
