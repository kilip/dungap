<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\State\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Dungap\State\Entity\State;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEntityListener(entity: State::class)]
final readonly class StateEntityListener
{
    public function __construct(
        private SerializerInterface $serializer,
        private HubInterface $hub
    ) {
    }

    public function postPersist(State $entity): void
    {
        $json = $this->serializer->serialize($entity, 'jsonld');
        $url = str_replace('.well-known/mercure', '', $this->hub->getPublicUrl());
        $this->hub->publish(new Update(
            $url.'states/latest/'.$entity->getName(),
            $json
        ));
    }
}
