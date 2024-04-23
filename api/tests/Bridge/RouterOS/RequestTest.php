<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\RouterOS;

use Dungap\Bridge\RouterOS\Request;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class RequestTest extends TestCase
{
    public function testRequest(): void
    {
        $client = $this->createMock(HttpClientInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $request = new Request(
            baseUrl: 'https://localhost',
            username: 'test',
            password: 'test',
            httpClient: $client
        );

        $client->expects($this->once())
            ->method('request')
            ->with('GET', '/rest/ip/address', $this->isType('array'))
            ->willReturn($response);
        $response->expects($this->once())
            ->method('toArray')
            ->with(true)
            ->willReturn($expected = ['hello' => 'world']);

        $return = $request->request('GET', '/ip/address');
        $this->assertSame($expected, $return);
    }
}
