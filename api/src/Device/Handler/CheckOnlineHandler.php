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

use Dungap\Contracts\Device\DeviceRepositoryInterface;
use Dungap\Contracts\Device\OnlineCheckerInterface;
use Dungap\Device\Command\CheckOnlineCommand;
use Dungap\Device\DTO\ResultDevice;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CheckOnlineHandler
{
    public function __construct(
        private DeviceRepositoryInterface $deviceRepository,
        #[Autowire('@dungap.device.online_checker')]
        private OnlineCheckerInterface $checker,
        private ?LoggerInterface $logger = null,
    ) {
    }

    public function __invoke(CheckOnlineCommand $command): void
    {
        touch($command->lockFile);

        try {
            $results = $this->checker->run();
            $this->processResults($results);
        } catch (\Exception $e) {
            $this->logger?->error('[OnlineChecker] error: {0}', [$e->getMessage()]);
        }

        unlink($command->lockFile);
    }

    /**
     * @param array<int,ResultDevice> $results
     */
    private function processResults(array $results): void
    {
        $repository = $this->deviceRepository;
        foreach ($results as $result) {
            $device = $repository->findByIpAddress($result->ipAddress);
            if (!is_null($device)) {
                $device->setOnline(true);
                $repository->store($device);
            }
        }
    }
}
