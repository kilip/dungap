<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Node\Controller;

use Dungap\Node\Command\PowerOnCommand;
use Dungap\Node\Entity\Node;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsController]
class PowerOnAction
{
    public function __construct(
        private MessageBusInterface $messageBus
    ) {
    }

    public function __invoke(Node $node): Response
    {
        $command = new PowerOnCommand($node->getId());
        $this->messageBus->dispatch($command);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
