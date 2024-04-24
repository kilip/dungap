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

final readonly class UptimeCheckProcessor implements UptimeProcessorInterface
{
    public function __construct(
        private SecureFactoryInterface $secureFactory,
    ) {
    }

    public function process(DeviceInterface $device): ?\DateTimeImmutable
    {
        $ssh = $this->secureFactory->createSshClient($device);
        $ssh->addCommand('uptime -s');
        $ssh->run();
        $output = $ssh->getOutput();

        return date_create_immutable_from_format('Y-m-d H:i:s', $output);
    }
}
