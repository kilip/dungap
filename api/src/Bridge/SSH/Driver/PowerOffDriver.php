<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\SSH\Driver;

use Dungap\Bridge\RouterOS\Constant;
use Dungap\Contracts\Device\SecureFactoryInterface;
use Dungap\Contracts\Feature\FeatureInterface;
use Dungap\Contracts\Feature\PowerOffDriverInterface;
use Psr\Log\LoggerInterface;

class PowerOffDriver implements PowerOffDriverInterface
{
    public function __construct(
        private SecureFactoryInterface $secureFactory,
        private LoggerInterface $logger
    ) {
    }

    public function getName(): string
    {
        return Constant::DriverName;
    }

    public function process(FeatureInterface $feature): bool
    {
        try {
            $device = $feature->getDevice();
            $ssh = $this->secureFactory->createSshClient($device);
            $ssh->addCommand('sudo poweroff');
            $ssh->run();

            return true;
        } catch (\Exception $e) {
            $this->logger->error('error while powering off device: {0}', [$e->getMessage()]);
        }

        return false;
    }
}
