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
use Dungap\Contracts\Device\EnumDeviceFeature;
use Dungap\Contracts\Device\PowerOffProcessorInterface;
use Dungap\Contracts\Device\SecureFactoryInterface;
use Psr\Log\LoggerInterface;

class PowerOffProcessor implements PowerOffProcessorInterface
{
    public function __construct(
        private SecureFactoryInterface $secureFactory,
        private ?LoggerInterface $logger = null
    ) {
    }

    public function supports(DeviceInterface $device): bool
    {
        return $device->hasFeature(EnumDeviceFeature::PowerOff) && $device->hasFeature(EnumDeviceFeature::SSH);
    }

    public function process(DeviceInterface $device): bool
    {
        try {
            $ssh = $this->secureFactory->createSshClient($device);
            $ssh->addCommand('sudo poweroff');
        } catch (\Exception $exception) {
            $this->logger?->error('Failed to poweroff by using SSH, message: {0}', [$exception->getMessage()]);

            return false;
        }

        return true;
    }
}
