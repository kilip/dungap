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

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\Service\ServiceInterface;
use Dungap\Core\Entity\UuidConcern;
use Dungap\Service\Repository\ServiceRepository;

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
#[ORM\Entity(repositoryClass: ServiceRepository::class)]
#[ORM\Table(name: 'dungap_service')]
#[ORM\UniqueConstraint(name: 'constraint_node_port', columns: ['node_id', 'port'])]
class Service implements ServiceInterface
{
    use UuidConcern;

    #[ORM\ManyToOne(targetEntity: NodeInterface::class)]
    private NodeInterface $node;

    #[ORM\Column(type: 'integer')]
    private int $port;

    #[ORM\Column(type: 'integer')]
    private int $timeout = 500;

    public function getStateName(): string
    {
        return "service.{$this->node->getName()}.{$this->port}";
    }

    public function getNode(): NodeInterface
    {
        return $this->node;
    }

    public function setNode(NodeInterface $node): void
    {
        $this->node = $node;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function setPort(int $port): void
    {
        $this->port = $port;
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
