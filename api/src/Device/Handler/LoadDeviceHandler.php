<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Device\Handler;

use Dungap\Contracts\Device\CategoryInterface;
use Dungap\Contracts\Device\CategoryRepositoryInterface;
use Dungap\Contracts\Device\DeviceRepositoryInterface;
use Dungap\Setting\Command\NewConfigurationCommand;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class LoadDeviceHandler
{
    public function __construct(
        private DeviceRepositoryInterface $deviceRepository,
        private CategoryRepositoryInterface $categoryRepository,
    ) {
    }

    public function __invoke(NewConfigurationCommand $command): void
    {
        $configs = $command->configs;

        foreach ($configs['devices'] as $device) {
            $this->loadDevice($device);
        }
    }

    /**
     * @param array<string,mixed> $device
     *
     * @return void
     */
    private function loadDevice(array $device)
    {
        $repository = $this->deviceRepository;
        $name = $device['name'];
        $ip = $device['ip'];
        $mac = $device['mac'];
        $hostname = $device['hostname'];
        $category = $this->loadCategory($device['category']);
        $device = $repository->findByIpOrName($ip, $name);

        if (is_null($device)) {
            $device = $repository->create();
        }

        if (!is_null($name)) {
            $device->setName($name);
        }
        if (!is_null($ip)) {
            $device->setIpAddress($ip);
        }
        if (!is_null($mac)) {
            $device->setMacAddress($mac);
        }
        if (!is_null($hostname)) {
            $device->setHostname($hostname);
        }

        $device->setCategory($category);

        $repository->store($device);
    }

    private function loadCategory(string $name = null): CategoryInterface
    {
        $name = $name ?? 'uncategorized';

        return $this->categoryRepository->findOrCreate($name);
    }
}
