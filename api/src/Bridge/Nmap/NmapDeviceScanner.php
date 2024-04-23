<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\Nmap;

use Dungap\Contracts\Device\DeviceScannerInterface;
use Dungap\Device\Command\ScanDeviceCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Process\Process;
use Symfony\Component\Uid\Uuid;

final readonly class NmapDeviceScanner implements DeviceScannerInterface
{
    public function __construct(
        private NmapResultParser $resultParser,
        private ?LoggerInterface $logger = null,
        private ?Process $process = null,

        #[Autowire('%dungap.cache_dir%/nmap')]
        private string $cacheDir = '/tmp/reboot/cache',

        #[Autowire('%dungap.nmap.template%')]
        private string $template = 'nmap -sn -n {target}'
    ) {
    }

    public function scan(ScanDeviceCommand $command): array
    {
        $filename = Uuid::v1();
        $resultFile = "{$this->cacheDir}/nmap/{$filename}.xml";
        $target = implode(' ', $command->target)." -oX {$resultFile}";
        $cmd = strtr($this->template, ['{target}' => $target]);
        $process = $this->process ?? new Process(
            explode(' ', $cmd),
        );

        if (!is_dir($dir = dirname($resultFile))) {
            mkdir($dir, 0777, true);
        }
        $process->start();
        $process->wait([$this, 'onProcess']);

        return $this->resultParser->parse($resultFile);
    }

    public function onProcess(string $type, string $buffer): void
    {
        $type = strtolower($type);
        $this->logger->notice("nmap.{$type}>> ".$buffer);
    }
}
