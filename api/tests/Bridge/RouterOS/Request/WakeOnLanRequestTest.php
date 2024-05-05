<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\RouterOS\Request;

use Dungap\Bridge\RouterOS\Contracts\HttpClientFactoryInterface;
use Dungap\Bridge\RouterOS\Exception;
use Dungap\Bridge\RouterOS\Request\WakeOnLanRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class WakeOnLanRequestTest extends TestCase
{
    public function testExecute(): void
    {
        $client = $this->createMock(HttpClientInterface::class);
        $factory = $this->createMock(HttpClientFactoryInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $mac = 'AA:BB:CC:DD:EE:FF';
        $json = [
            'mac' => $mac,
            'interface' => 'ether1',
        ];

        $factory->expects($this->exactly(2))
            ->method('create')
            ->willReturn($client);

        $client->expects($this->exactly(2))
            ->method('request')
            ->with('POST', '/rest/tool/wol', [
                'json' => $json,
            ])
            ->willReturnCallback(function () use ($response) {
                static $counter = 1;
                // should throw on second execution to test exception
                if ($counter > 1) {
                    throw new \Exception('hello world');
                }
                ++$counter;

                return $response;
            })
        ;

        $wol = new WakeOnLanRequest($factory, 'ether1');
        $wol->execute($mac);

        $this->expectException(Exception::class);
        $wol->execute($mac);
    }
}
