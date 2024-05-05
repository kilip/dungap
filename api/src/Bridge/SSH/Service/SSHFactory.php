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
use Dungap\Bridge\SSH\Contracts\NodeConfigInterface;
use Dungap\Bridge\SSH\Contracts\NodeConfigRepositoryInterface;
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\SSH\SshFactoryInterface;
use Dungap\Contracts\SSH\SshInterface;
use phpseclib3\Crypt\PublicKeyLoader;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class SSHFactory implements SshFactoryInterface
{
    public function __construct(
        private NodeConfigRepositoryInterface $configRepository,
        private LoggerInterface $logger,
        #[Autowire('%env(DUNGAP_DEFAULT_SSH_USERNAME)%')]
        private string $defaultUsername,
        #[Autowire('%env(DUNGAP_DEFAULT_SSH_PASSWORD)%')]
        private string $defaultPassword,
        #[Autowire('%env(resolve:DUNGAP_DEFAULT_SSH_PRIVATE_KEY)%')]
        private string $defaultPrivateKey,
        #[Autowire('%env(DUNGAP_DEFAULT_SSH_TIMEOUT)%')]
        private int $defaultTimeout = 5,
        #[Autowire('%env(DUNGAP_DEFAULT_SSH_PORT)%')]
        private int $defaultPort = 22
    ) {
    }

    public function createSshClient(NodeInterface $node): SshInterface
    {
        $nodeConfig = $this->configRepository->findByNode($node);
        $host = $node->getIp() ?? $node->getHostname();
        $username = $this->defaultUsername;
        $password = $this->defaultPassword;
        $privateKey = $this->defaultPrivateKey;
        $timeout = $this->defaultTimeout;
        $port = $this->defaultPort;

        if ($nodeConfig instanceof NodeConfigInterface) {
            $username = $nodeConfig->getUsername();
            $password = $nodeConfig->getPassword() ?? $password;
            $privateKey = $nodeConfig->getPrivateKey() ?? $privateKey;
            $timeout = $nodeConfig->getTimeout();
            $port = $nodeConfig->getPort();
        }

        if (is_file($privateKey)) {
            $privateKey = file_get_contents($privateKey);
        }
        $key = PublicKeyLoader::load($privateKey);

        $config = new Configuration(
            host: $host,
            username: $username,
            port: $port,
            timeout: $timeout,
            key: $key,
            password: $password
        );

        return new SSH($config, $this->logger);
    }
}
