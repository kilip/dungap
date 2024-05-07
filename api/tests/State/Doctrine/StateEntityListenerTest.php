<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\State\Doctrine;

use Dungap\State\Doctrine\StateEntityListener;
use Dungap\State\Entity\State;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Serializer\SerializerInterface;

class StateEntityListenerTest extends KernelTestCase
{
    private MockObject|SerializerInterface $serializer;
    private MockObject|HubInterface $hub;
    private StateEntityListener $listener;

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->hub = $this->createMock(HubInterface::class);
        $this->listener = new StateEntityListener($this->serializer, $this->hub);
    }

    public function testPostPersist(): void
    {
        $state = new State();
        $state->setName('some.name');
        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with($state, 'jsonld')
            ->willReturn('{}')
        ;
        $this->hub->expects($this->once())
            ->method('getPublicUrl')
            ->willReturn('https://localhost/.well-known/mercure');

        $this->hub->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(Update::class));

        $this->listener->postPersist($state);
    }
}
