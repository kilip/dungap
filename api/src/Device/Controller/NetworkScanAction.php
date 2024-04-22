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

use Dungap\Device\Command\NetworkScanCommand;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsController]
final readonly class NetworkScanAction
{
    public function __construct(
        private MessageBusInterface $messageBus
    ) {
    }

    public function __invoke(
        #[MapRequestPayload]
        NetworkScanCommand $command,
    ): Response {
        $this->messageBus->dispatch($command);

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
