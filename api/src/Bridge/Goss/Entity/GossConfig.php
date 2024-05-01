<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\Goss\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Doctrine\ORM\Mapping as ORM;
use Dungap\Bridge\Goss\Contracts\GossConfigInterface;
use Dungap\Contracts\Service\ServiceInterface;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    operations: [
        new Post(
            security: 'is_granted("ROLE_ADMIN")',
        ),
        new GetCollection(),
        new Get(),
        new Patch(
            security: 'is_granted("ROLE_ADMIN")',
        ),
        new Put(
            security: 'is_granted("ROLE_ADMIN")',
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN")',
        ),
    ],
    mercure: true
)]
#[ORM\Entity()]
class GossConfig implements GossConfigInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private ?Uuid $id = null;

    #[ORM\OneToOne(
        targetEntity: ServiceInterface::class,
        cascade: ['persist', 'remove'],
        fetch: 'EAGER',
        orphanRemoval: true
    )]
    private ServiceInterface $service;

    #[ORM\Column(type: 'string', length: 10)]
    private string $type;

    #[ORM\Column(type: 'integer')]
    private int $timeout;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getService(): ServiceInterface
    {
        return $this->service;
    }

    public function setService(ServiceInterface $service): void
    {
        $this->service = $service;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function setTimeout(int $timeout): void
    {
        $this->timeout = $timeout;
    }
}
