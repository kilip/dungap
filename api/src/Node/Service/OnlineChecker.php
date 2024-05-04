<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Node\Service;

use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\Node\OnlineCheckerInterface;
use Dungap\Node\State\PingReport;
use Symfony\Component\Process\Process;

final readonly class OnlineChecker implements OnlineCheckerInterface
{
    public function __construct(
        private int $timeout = 1
    ) {
    }

    public function check(NodeInterface $node): PingReport
    {
        $address = $node->getIp() ?? $node->getHostname();

        return $this->ping($address);
    }

    private function ping(string $host): PingReport
    {
        $ttl = 255;
        $timeout = $this->timeout;

        // @codeCoverageIgnoreStart
        if ('WIN' === strtoupper(substr(PHP_OS, 0, 3))) {
            // -n = number of pings; -i = ttl; -w = timeout (in milliseconds).
            $commands = 'ping -n 1 -i '.$ttl.' -w '.($timeout * 1000).' '.$host;
        }
        // Exec string for Darwin based systems (OS X).
        elseif ('DARWIN' === strtoupper(PHP_OS)) {
            // -n = numeric output; -c = number of pings; -m = ttl; -t = timeout.
            $commands = 'ping -n -c 1 -m '.$ttl.' -t '.$timeout.' '.$host;
        }
        // Exec string for other UNIX-based systems (Linux).
        else {
            // -n = numeric output; -c = number of pings; -t = ttl; -W = timeout
            $commands = 'ping -n -c 1 -t '.$ttl.' -W '.$timeout.' '.$host.' 2>&1';
        }
        // @codeCoverageIgnoreEnd

        $process = Process::fromShellCommandline($commands);
        $output = '';
        $exitCode = $process->run(function ($type, $buffer) use (&$output) {
            $output .= $buffer;
        });

        // Search for a 'time' value in the result line.
        $response = preg_match("/time(?:=|<)(?<time>[\.0-9]+)(?:|\s)ms/", $output, $matches);
        $latency = null;
        if ($response > 0 && isset($matches['time'])) {
            $time = floatval($matches['time']);
            $latency = round($time, 4);
        }

        return new PingReport(
            0 === $exitCode,
            $latency
        );
    }
}
