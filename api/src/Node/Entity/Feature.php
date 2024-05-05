<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Node\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use Dungap\Contracts\Node\FeatureInterface;
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Core\Entity\UuidConcern;
use Dungap\Node\Repository\FeatureRepository;

#[ApiResource(
    operations: [
        new Post(security: 'is_granted("ROLE_ADMIN")'),
        new GetCollection(),
        new Get(),
        new Patch(security: 'is_granted("ROLE_ADMIN")'),
        new Delete(security: 'is_granted("ROLE_ADMIN")'),
    ],
    mercure: true
)]
#[ORM\Entity(repositoryClass: FeatureRepository::class)]
#[ORM\UniqueConstraint(name: 'feature_node', columns: ['node_id', 'name'])]
class Feature implements FeatureInterface
{
    use UuidConcern;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\ManyToOne(targetEntity: NodeInterface::class)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private NodeInterface $node;

    #[ORM\Column(type: 'string')]
    private string $driver;

    public function getNode(): NodeInterface
    {
        return $this->node;
    }

    public function setNode(NodeInterface $node): void
    {
        $this->node = $node;
    }

    public function getDriver(): string
    {
        return $this->driver;
    }

    public function setDriver(string $driver): void
    {
        $this->driver = $driver;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
