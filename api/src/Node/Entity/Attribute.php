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
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Core\Entity\UuidConcern;

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
#[ORM\Entity()]
#[ORM\Table(name: 'node_attribute')]
#[ORM\UniqueConstraint(
    name: 'attribute_node',
    columns: ['subject_id', 'name']
)]
class Attribute
{
    use UuidConcern;

    #[ORM\ManyToOne(
        targetEntity: NodeInterface::class,
        inversedBy: 'attributes'
    )]
    private NodeInterface $subject;

    #[ORM\Column(type: 'string', length: 50)]
    private string $name;

    /**
     * Value data type.
     */
    #[ORM\Column(type: 'string', length: 50)]
    private string $type;

    #[ORM\Column(type: 'json')]
    private array $value;

    public function __toString()
    {
        return $this->name;
    }

    public function getSubject(): NodeInterface
    {
        return $this->subject;
    }

    public function setSubject(NodeInterface $subject): void
    {
        $this->subject = $subject;
    }

    public function getValue(): int|string|float|bool|object
    {
        return $this->value[0];
    }

    public function setValue(int|string|float|bool|object $value): void
    {
        $this->value = [$value];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
