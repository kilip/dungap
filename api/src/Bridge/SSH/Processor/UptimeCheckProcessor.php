<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\SSH\Processor;

use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Device\SecureFactoryInterface;
use Dungap\Contracts\Device\UptimeProcessorInterface;
use Psr\Log\LoggerInterface;

final readonly class UptimeCheckProcessor implements UptimeProcessorInterface
{
    public function __construct(
        private SecureFactoryInterface $secureFactory,
        private ?LoggerInterface $logger = null,
    ) {
    }

    public function process(DeviceInterface $device): ?\DateTimeImmutable
    {
        try {
            $ssh = $this->secureFactory->createSshClient($device);
            $ssh->addCommand('uptime -s');
            $ssh->run();
            $output = trim($ssh->getOutput());
            $this->logger?->info('processing date time for {0}: {1}', [$device->getIpAddress(), $output]);
            $exp = explode("\n", $output);
            $lastLine = $output[count($exp) - 1];
            $this->logger?->notice('Last Update Time {0}', [$lastLine]);
            $value = date_create_immutable_from_format('Y-m-d H:i:s', $lastLine);

            if ($value instanceof \DateTimeImmutable) {
                return $value;
            }
        } catch (\Exception $e) {
            $this->logger?->error($e->getMessage());
        }

        return null;
    }
}
