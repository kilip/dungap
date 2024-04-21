<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Network\Controller;

use Dungap\Network\Command\ScanNodesCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsController]
class ScanNodesAction
{
    public function __construct(
        private MessageBusInterface $messageBus
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $data = $request->toArray();

        if (!array_key_exists('target', $data)) {
            return new Response('Missing target data', 400);
        }

        $target = $data['target'];
        $this->messageBus->dispatch(new ScanNodesCommand($target));

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
