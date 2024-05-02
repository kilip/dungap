<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\Service;

use Dungap\Contracts\Device\DeviceInterface;
use Symfony\Component\Uid\Uuid;

interface ServiceRepositoryInterface
{
    public function create(): ServiceInterface;

    public function findByPort(DeviceInterface $device, int $port): ?ServiceInterface;

    /**
     * Register service when it's not exists in database.
     */
    public function register(ServiceInterface $service): void;

    public function store(ServiceInterface $service): void;
}
