<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Service\Service;

use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\Service\ScannerConfigInterface;
use Dungap\Contracts\Service\ServiceScannerInterface;
use Dungap\Contracts\Service\ServiceValidatorInterface;
use Dungap\Dungap;
use Dungap\Service\Config\ScannerConfig;
use Dungap\Service\Event\ServiceScannedEvent;
use Dungap\Service\ServiceException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class ServiceScanner implements ServiceScannerInterface
{
    /**
     * @var iterable<ScannerConfigInterface>
     */
    private iterable $configs = [];

    /**
     * @param array<int,mixed> $configs
     */
    public function __construct(
        private readonly ServiceValidatorInterface $validator,
        private readonly EventDispatcherInterface $dispatcher,
        #[Autowire('%dungap.service.scanner.configs%')]
        array $configs
    ) {
        foreach ($configs as $config) {
            $this->addConfig($config['port'], $config['timeout']);
        }
    }

    public function scan(NodeInterface $node): void
    {
        if (!is_null($node->getIp())) {
            $this->doScan($node);
        }
    }

    public function addConfig(int $port, int $timeout): void
    {
        $this->configs[] = new ScannerConfig($port, $timeout);
    }

    private function doScan(NodeInterface $node): void
    {
        $validator = $this->validator;
        $dispatcher = $this->dispatcher;
        $configs = $this->configs;

        foreach ($configs as $config) {
            try {
                $report = $validator->validate(
                    $node,
                    $config->getPort(),
                    $config->getTimeout()
                );
                $event = new ServiceScannedEvent($report);
                $dispatcher->dispatch($event, Dungap::OnServiceScanned);
            } catch (\Exception $e) {
                throw ServiceException::serviceScanFailed($node->getName(), $config->getPort(), $e->getMessage());
            }
        }
    }
}
