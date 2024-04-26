<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Security\Listener;

use Dungap\Contracts\UserInterface;
use Dungap\Security\Listener\JWTAuthenticationSuccessListener;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use PHPUnit\Framework\TestCase;

class JWTAuthenticationSuccessListenerTest extends TestCase
{
    public function testInvoke(): void
    {
        $event = $this->createMock(AuthenticationSuccessEvent::class);
        $user = $this->createMock(UserInterface::class);

        $event->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($user));

        $event->expects($this->once())
            ->method('getData')
            ->willReturn([]);

        $event->expects($this->once())
            ->method('setData')
            ->with($this->isType('array'));

        $listener = new JWTAuthenticationSuccessListener();

        $listener($event);
    }
}
