<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Service\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Doctrine\ORM\Mapping as ORM;
use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Service\ServiceInterface;
use Dungap\Service\Repository\ServiceRepository;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    operations: [
        new Post(security: 'is_granted("ROLE_ADMIN")'),
        new GetCollection(),
        new Get(),
        new Put(security: 'is_granted("ROLE_ADMIN")'),
        new Patch(security: 'is_granted("ROLE_ADMIN")'),
        new Delete(security: 'is_granted("ROLE_ADMIN")'),
    ],
    mercure: true
)]
#[ORM\Entity(repositoryClass: ServiceRepository::class)]
#[ORM\UniqueConstraint(name: 'device_port', columns: ['device_id', 'port'])]
class Service implements ServiceInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(
        targetEntity: DeviceInterface::class,
        fetch: 'LAZY',
        inversedBy: 'services'
    )]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    #[ApiProperty(fetchEager: false)]
    private DeviceInterface $device;

    #[ORM\Column(type: 'integer')]
    private int $port;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getDevice(): DeviceInterface
    {
        return $this->device;
    }

    public function setDevice(DeviceInterface $device): void
    {
        $this->device = $device;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function setPort(int $port): void
    {
        $this->port = $port;
    }
}
