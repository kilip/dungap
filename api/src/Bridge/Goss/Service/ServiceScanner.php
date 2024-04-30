<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\Goss\Service;

use Dungap\Bridge\Goss\Constant;
use Dungap\Bridge\Goss\Contracts\GossConfigFactoryInterface;
use Dungap\Bridge\Goss\Contracts\GossConfigRepositoryInterface;
use Dungap\Bridge\Goss\Contracts\GossInterface;
use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Service\ServiceRepositoryInterface;
use Dungap\Contracts\Setting\ConfigInterface\ConfigInterface;

class ServiceScanner
{
    public function __construct(
        private ConfigInterface $config,
        private ServiceRepositoryInterface $serviceRepository,
        private GossConfigFactoryInterface $configFactory,
        private GossConfigRepositoryInterface $gossRepository,
        private GossInterface $goss
    ) {
    }

    public function scan(DeviceInterface $device): void
    {
        $config = $this->config->getScanner();
        $configs = [];

        foreach ($config as $item) {
            $service = $this->serviceRepository->create();
            $service->setPort($item['port']);
            $service->setDevice($device);

            $gossConfig = $this->gossRepository->create();
            $gossConfig->setService($service);
            $gossConfig->setTimeout($item['timeout']);
            $gossConfig->setType(Constant::ValidatorTypeAddress);
            $configs[] = $gossConfig;
        }

        $configFile = $this->configFactory->create($configs);
        $this->goss->run($configFile);
    }
}
