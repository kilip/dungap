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

use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Device\SecureFactoryInterface;
use Dungap\Contracts\Device\SftpInterface;
use Dungap\Contracts\Device\SshInterface;
use Dungap\Contracts\Setting\SettingFactoryInterface;
use Psr\Log\LoggerInterface;

class SecureFactory implements SecureFactoryInterface
{
    public function __construct(
        private SettingFactoryInterface $settingFactory,
        private ?LoggerInterface $logger = null,
    ) {
    }

    public function createSshClient(DeviceInterface $device): SshInterface
    {
        $config = $this->loadConfig($device);

        return new SSH(
            host: $device->getIpAddress(),
            setting: $config,
            logger: $this->logger
        );
    }

    public function createSftpClient(DeviceInterface $device): SftpInterface
    {
        $config = $this->loadConfig($device);

        return new SFTP(
            host: $device->getIpAddress(),
            setting: $config,
            logger: $this->logger
        );
    }

    private function loadConfig(DeviceInterface $device): Setting
    {
        $settings = $this->settingFactory;

        $deviceId = $device->getId();
        $setting = $settings->get("ssh.config.{$deviceId}", Setting::class, false);

        if (is_null($setting)) {
            $setting = $settings->get('ssh.config.global', Setting::class);
        }

        return $setting;
    }
}
