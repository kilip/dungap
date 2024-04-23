<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\SSH;

use Dungap\Bridge\SSH\Setting;
use Dungap\Bridge\SSH\SFTP;
use phpseclib3\Net\SFTP as NetSFTP;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SFTPTest extends TestCase
{
    private Setting $setting;
    private SFTP $sftp;
    private MockObject|NetSFTP $client;

    protected function setUp(): void
    {
        $this->client = $this->createMock(NetSFTP::class);
        $this->setting = new Setting(
            username: 'johndoe',
            privateKey: __DIR__.'/fixtures/dungap'
        );
        $this->sftp = new SFTP(
            host: '192.168.1.1',
            setting: $this->setting,
            client: $this->client
        );

        $this->client->expects($this->once())
            ->method('login')
            ->willReturn(true);
    }

    public function testUpload(): void
    {
        $this->client->expects($this->once())
            ->method('put')
            ->with($remote = '/tmp/remote/file', $local = '/tmp/local/file', $this->anything());

        $this->sftp->upload($local, $remote);
    }

    public function testDownload(): void
    {
        $this->client->expects($this->once())
            ->method('get')
            ->with($remote = '/tmp/remote/file', $local = '/tmp/local/file', $this->anything());

        $this->sftp->download($remote, $local);
    }
}
