<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Core\Cli;

use Dungap\Contracts\Core\TaskRunnerInterface;
use Dungap\Dungap;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsCommand(name: 'dungap:task:run')]
final class TaskCommand extends Command
{
    public function __construct(
        private readonly EventDispatcherInterface $dispatcher,
        private readonly TaskRunnerInterface $runner,
        #[Autowire('%kernel.environment%')]
        private string $env
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $terminate = false;
        $runner = $this->runner;
        $dispatcher = $this->dispatcher;

        // @codeCoverageIgnoreStart
        if (function_exists('pcntl_signal')) {
            pcntl_async_signals(true);
            pcntl_signal(SIGINT, function () use (&$terminate) {
                $terminate = true;
            });
        }
        // @codeCoverageIgnoreEnd

        if ('test' == $this->env) {
            $terminate = true;
        }

        $dispatcher->dispatch($this, Dungap::OnTaskPreRun);
        while (true) {
            $runner->run($output);
            if ($terminate) {
                $output->writeln('Received terminate signals');
                break;
            }
        }

        return self::SUCCESS;
    }
}
