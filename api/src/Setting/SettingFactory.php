<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Setting;

use Dungap\Contracts\Setting\SettingFactoryInterface;
use Dungap\Contracts\Setting\SettingRepositoryInterface;
use Dungap\Setting\Entity\Setting;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
final readonly class SettingFactory implements SettingFactoryInterface
{
    public function __construct(
        private SettingRepositoryInterface $settingRepository,
    ) {
    }

    public function get(string $key, string $className): object
    {
        $setting = $this->settingRepository->findByKey($key);

        if (is_null($setting)) {
            $setting = new Setting();
            $setting->setKey($key);
            $setting->setValue(new $className());
            $this->settingRepository->store($setting);
        }

        return $setting->getValue();
    }

    public function save(string $key, object $setting): void
    {
        $storage = $this->settingRepository->findByKey($key);

        $storage->setValue($setting);
        $this->settingRepository->store($storage);
    }
}
