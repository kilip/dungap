<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Service\Handler;

use Dungap\Contracts\Service\ServiceInterface;
use Dungap\Contracts\Service\ServiceRepositoryInterface;
use Dungap\Contracts\Service\ServiceValidatorInterface;
use Dungap\Dungap;
use Dungap\Service\Command\ServiceCheckCommand;
use Dungap\Service\ServiceException;
use Dungap\State\Event\StateUpdatedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsMessageHandler]
final readonly class ServiceCheckHandler
{
    public function __construct(
        private ServiceRepositoryInterface $services,
        private ServiceValidatorInterface $validator,
        private EventDispatcherInterface $dispatcher,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(ServiceCheckCommand $command): void
    {
        foreach ($this->services->findAll() as $service) {
            try {
                $this->doValidate($service);
            } catch (\Exception $e) {
                $this->logger->error('Failed to check service on node {0} port {1}. Error: ', [
                    $service->getNode()->getName(),
                    $service->getPort(),
                    $e->getMessage(),
                ]);
            }
        }
    }

    private function doValidate(ServiceInterface $service): void
    {
        $validator = $this->validator;
        $dispatcher = $this->dispatcher;
        try {
            $report = $validator->validate($service->getNode(), $service->getPort());
        } catch (\Exception $e) {
            throw ServiceException::failedToValidateService($service, $e);
        }

        try {
            $event = new StateUpdatedEvent(
                entity: $service,
                name: $service->getStateName(),
                state: $report->isSuccessful() ? Dungap::OnlineState : Dungap::OfflineState,
                attributes: ['latency' => $report->getLatency()],
                related: $service->getNode()
            );
            $dispatcher->dispatch($event, Dungap::OnServiceValidated);
        } catch (\Exception $e) {
            throw ServiceException::failedToDispatchValidatedEvent($service, $e);
        }
    }
}
