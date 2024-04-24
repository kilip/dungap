<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\Device;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('dungap.processor.power_off')]
interface PowerOffProcessorInterface
{
    public function supports(DeviceInterface $device): bool;

    public function process(DeviceInterface $device): bool;
}
