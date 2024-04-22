<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Device\Controller;

use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Device\Command\PowerOffCommand;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsController]
class PowerOffAction
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(
        DeviceInterface $device
    ): Response {
        $command = new PowerOffCommand($device->getId());
        $this->messageBus->dispatch($command);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
