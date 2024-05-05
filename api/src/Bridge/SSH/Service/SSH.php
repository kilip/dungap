<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\SSH\Service;

use Dungap\Bridge\SSH\Configuration;
use Dungap\Bridge\SSH\SSHException;
use Dungap\Contracts\SSH\SshInterface;
use phpseclib3\Net\SSH2;
use Psr\Log\LoggerInterface;

define('NET_SSH2_LOGGING', SSH2::LOG_SIMPLE);

final class SSH implements SshInterface
{
    private SSH2 $client;
    private bool $loggedIn = false;

    public function __construct(
        private readonly Configuration $config,
        private LoggerInterface $logger,
        SSH2 $client = null,
    ) {
        $this->client = $client ?? new SSH2(
            host: $this->config->host,
            port: $this->config->port,
            timeout: $this->config->timeout,
        );
    }

    public function execute(string $command, callable $callback = null): string
    {
        $this->ensureLoggedIn();

        return $this->client->exec($command, $callback);
    }

    public function getLogs(): array
    {
        return $this->client->getLog();
    }

    public function disconnect(): void
    {
        $this->client->disconnect();
    }

    private function ensureLoggedIn(): void
    {
        $config = $this->config;

        $this->logger->info(
            'Start ssh connect with config: {0}',
            [$config]
        );

        if (!$this->loggedIn) {
            $password = $config->key ?? $config->password;
            if (!$this->client->login($config->username, $password)) {
                throw SSHException::failToLogin($config);
            }
        }
    }
}
