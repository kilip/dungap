<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\Goss;

use Dungap\Bridge\Goss\Contracts\GossFileInterface;
use Dungap\Bridge\Goss\Contracts\GossReportFactoryInterface;
use Dungap\Bridge\Goss\Contracts\GossReportInterface;
use Dungap\Bridge\Goss\Contracts\GossInterface;
use Dungap\Bridge\Goss\GossException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

final class Goss implements GossInterface
{
    public function __construct(
        private readonly GossReportFactoryInterface $reportFactory,
        #[Autowire('%env(DUNGAP_GOSS_EXECUTABLE)%')]
        private readonly string $executableFile,
        private readonly LoggerInterface $logger,
        #[Autowire('%env(DUNGAP_GOSS_SLEEP)%')]
        private readonly string $sleep = '2s',
        #[Autowire('%env(DUNGAP_GOSS_RETRY_TIMEOUT)%')]
        private string $retryTimeout = '10s',
        private ?Process $process = null,
    ) {
    }

    public function validate(GossFileInterface $configFile): GossReportInterface
    {
        $executableFile = $this->executableFile;

        if (!is_file($executableFile)) {
            $finder = new ExecutableFinder();
            if (!is_null($file = $finder->find($executableFile))) {
                $executableFile = $file;
            }
        }

        if (!file_exists($executableFile) || !is_executable($executableFile)) {
            throw GossException::executableFileInvalid($executableFile);
        }

        if (!file_exists($configFile->getFileName()) || !is_readable($configFile->getFileName())) {
            throw GossException::invalidGossConfigFile($configFile->getFileName());
        }

        $commands = [
            $executableFile,
            'validate',
            $configFile->getFileName(),
        ];

        $env = [
            'GOSS_FMT' => 'json',
            'GOSS_FILE' => $configFile->getFileName(),
            'GOSS_RETRY_TIMEOUT' => $this->retryTimeout,
            'GOSS_SLEEP' => $this->sleep,
            'GOSS_MAX_CONCURRENT' => 1,
        ];

        if (PHP_OS_FAMILY == 'Windows') {
            $env['GOSS_USE_ALPHA'] = 1;
        }

        $process = $this->process ?? new Process($commands, null, $env);

        // @codeCoverageIgnoreStart
        $output = '';
        $callback = function (string $type, string $buffer) use (&$output) {
            $output .= $buffer;
        };
        // @codeCoverageIgnoreEnd

        $this->logger->notice('start running goss validator', $commands);
        $exitCode = $process->run($callback);
        $this->logger->notice('goss exit code {0}', [$exitCode]);

        try {
            $json = '{}';
            if (preg_match_all('/{"results.*}$/im', $output, $matches)) {
                $num = count($matches[0]);
                $json = $matches[0][$num - 1];
            }

            return $this->reportFactory->create($json);
        } catch (\Exception $e) {
            throw GossException::createOutputFailed($output, $e->getMessage());
        }
    }
}
