<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\SSH;

use Dungap\Contracts\Device\SftpInterface;
use phpseclib3\Net\SFTP as NetSFTP;
use Psr\Log\LoggerInterface;

final readonly class SFTP implements SftpInterface
{
    use ClientTrait;

    public function __construct(
        private string $host,
        private Setting $setting,
        private ?LoggerInterface $logger = null,
        private ?NetSFTP $client = null,
    ) {
    }

    public function upload(string $localSource, string $remoteTarget): void
    {
        // TODO: create remote directory if not exists
        $setting = $this->setting;
        $client = $this->client ?? new NetSFTP(
            $this->host,
            $setting->port,
            $setting->timeout
        );

        $this->logger?->info('[SFTP] uploading from {0} to {1}', [$localSource, $remoteTarget]);
        $this->login($client, $setting);
        $client->put($remoteTarget, $localSource);
    }

    public function download(string $remoteSource, string $localTarget): void
    {
        $setting = $this->setting;
        $client = $this->client ?? new NetSFTP(
            $this->host,
            $setting->port,
            $setting->timeout
        );

        $this->login($client, $setting);

        // @codeCoverageIgnoreStart
        if (!is_dir($dir = dirname($localTarget))) {
            mkdir($dir, 0777, true);
        }
        // @codeCoverageIgnoreEnd

        $this->logger?->info('[SFTP] downloading from {0} to {1}', [$remoteSource, $localTarget]);
        $client->get($remoteSource, $localTarget);
    }
}
