<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Device\Handler;

use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Device\DeviceRepositoryInterface;
use Dungap\Contracts\Device\EnumDeviceFeature;
use Dungap\Contracts\Device\OnlineCheckerInterface;
use Dungap\Contracts\Device\UptimeProcessorInterface;
use Dungap\Device\Command\CheckOnlineCommand;
use Dungap\Device\DTO\ResultDevice;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
readonly class CheckOnlineHandler
{
    /**
     * @param array<int,UptimeProcessorInterface> $uptimeProcessors
     */
    public function __construct(
        private DeviceRepositoryInterface $deviceRepository,
        #[Autowire('@dungap.device.online_checker')]
        private OnlineCheckerInterface $checker,
        #[TaggedIterator('dungap.processor.uptime')]
        private iterable $uptimeProcessors,
        private ?LoggerInterface $logger = null,
    ) {
    }

    public function __invoke(CheckOnlineCommand $command): void
    {
        touch($command->lockFile);

        try {
            $results = $this->checker->run();
            $this->logger->notice('[OnlineChecker] processing results...');
            foreach ($results as $result) {
                $this->logger?->info('[OnlineChecker] processing result {0}', [$result->ipAddress]);
                $this->processResult($result);
            }
        } catch (\Exception $e) {
            $this->logger?->error('[OnlineChecker] error: {0}', [$e->getMessage()]);
        }

        unlink($command->lockFile);
    }

    private function processResult(ResultDevice $resultDevice): void
    {
        $repository = $this->deviceRepository;
        $device = $repository->findByIpAddress($resultDevice->ipAddress);

        if (is_null($device)) {
            return;
        }

        if ($device->isOnline() !== $resultDevice->online) {
            $device->setOnline($resultDevice->online);
        }

        if (!$device->isOnline()) {
            $device->setUptime(null);
        }
        if ($device->isOnline() && is_null($device->getUptime()) && $device->hasFeature(EnumDeviceFeature::Uptime)) {
            $this->logger->notice('online.checker>> checking uptime for {0}', [$device->getIpAddress()]);
            $uptime = $this->generateUptime($device);
            $device->setUptime($uptime);
        }

        $repository->store($device);
    }

    private function generateUptime(DeviceInterface $device): \DateTimeImmutable
    {
        foreach ($this->uptimeProcessors as $uptimeProcessor) {
            $uptime = $uptimeProcessor->process($device);
            if (!is_null($uptime)) {
                return $uptime;
            }
        }

        return new \DateTimeImmutable();
    }
}
