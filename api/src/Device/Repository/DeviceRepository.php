<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Device\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Device\DeviceRepositoryInterface;
use Dungap\Device\Entity\Device;

/**
 * @extends ServiceEntityRepository<Device>
 */
class DeviceRepository extends ServiceEntityRepository implements DeviceRepositoryInterface
{
    public function __construct(ManagerRegistry $manager)
    {
        parent::__construct($manager, Device::class);
    }

    public function findById(string $id): ?DeviceInterface
    {
        return $this->find($id);
    }

    public function findByMacAddress(string $macAddress): ?DeviceInterface
    {
        return $this->findOneBy(['macAddress' => $macAddress]);
    }

    public function findByIpAddress(string $ipAddress): ?DeviceInterface
    {
        return $this->findOneBy(['ipAddress' => $ipAddress]);
    }

    public function findByHostname(string $hostname): ?DeviceInterface
    {
        return $this->findOneBy(['hostname' => $hostname]);
    }

    public function create(): DeviceInterface
    {
        return new Device();
    }

    public function store(DeviceInterface $device): void
    {
        $this->getEntityManager()->persist($device);
        $this->getEntityManager()->flush();
    }
}
