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

use Dungap\Contracts\Device\SshInterface;
use phpseclib3\Net\SSH2;
use Psr\Log\LoggerInterface;

final class SSH implements SshInterface
{
    use ClientTrait;

    /**
     * @var array<int,string>
     */
    private array $commands = [];

    public function __construct(
        private readonly string $host,
        private readonly Setting $setting,
        private readonly ?LoggerInterface $logger = null,
        private readonly ?SSH2 $client = null,
    ) {
    }

    public function addCommand(string $command): SshInterface
    {
        $this->commands[] = $command;

        return $this;
    }

    public function run(): void
    {
        $setting = $this->setting;
        $client = $this->client ?? new SSH2(
            $this->host,
            $setting->port,
            $setting->timeout
        );

        $this->login($client, $setting);

        foreach ($this->commands as $command) {
            $client->exec($command, [$this, 'onRun']);
        }
    }

    public function onRun(string $output): void
    {
        $this->logger?->info("[SSH] {$output}");
    }
}
