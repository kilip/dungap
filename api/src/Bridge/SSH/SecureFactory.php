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
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class SecureFactory implements SecureFactoryInterface
{
    public function __construct(
        private SettingFactoryInterface $settingFactory,
        #[Autowire('%env(SSH_USERNAME)%')]
        private string                  $username,
        #[Autowire('%env(resolve:SSH_PRIVATE_KEY)%')]
        private string                  $privateKey,
        private ?LoggerInterface        $logger = null,
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
            $setting = $settings->get('ssh.config.global', Setting::class, false);
            if(is_null($setting)){
                $privateKey = $this->privateKey;
                if(is_file($privateKey)){
                    $this->logger?->info('loading private key from: ', [$privateKey]);
                    $privateKey = file_get_contents($privateKey);
                }
                $setting = new Setting(
                    username: $this->username,
                    privateKey: $privateKey,
                    port: 22,
                );
                $settings->save('ssh.config.global', $setting);
            }
        }

        return $setting;
    }
}
