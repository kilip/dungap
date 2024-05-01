<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\Goss\Service;

use Dungap\Bridge\Goss\Contracts\GossConfigFileInterface;
use Dungap\Bridge\Goss\Contracts\GossReportFactoryInterface;
use Dungap\Bridge\Goss\Contracts\GossReportInterface;
use Dungap\Bridge\Goss\GossException;
use Dungap\Bridge\Goss\Service\ServiceValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class ServiceValidatorTest extends TestCase
{
    private MockObject|GossReportFactoryInterface $reportFactory;
    private MockObject|Process $process;
    private MockObject|GossConfigFileInterface $configFile;
    private MockObject|GossReportInterface $report;
    private ServiceValidator $validator;
    private string $executableFile = 'goss';

    protected function setUp(): void
    {
        $finder = new ExecutableFinder();

        $this->reportFactory = $this->createMock(GossReportFactoryInterface::class);
        $this->executableFile = $finder->find('php');
        $this->process = $this->createMock(Process::class);
        $this->configFile = $this->createMock(GossConfigFileInterface::class);
        $this->report = $this->createMock(GossReportInterface::class);

        $this->configFile->expects($this->any())
            ->method('getFileName')
            ->willReturn(__FILE__);
        $this->validator = new ServiceValidator(
            reportFactory: $this->reportFactory,
            executableFile: $this->executableFile,
            process: $this->process
        );
    }

    public function testValidateWithInexistentExecutable(): void
    {
        $validator = new ServiceValidator(
            $this->reportFactory,
            __FILE__
        );
        $this->expectException(GossException::class);
        $validator->validate($this->configFile);
    }

    public function testValidateWithInxistentConfigFile(): void
    {
        $configFile = $this->createMock(GossConfigFileInterface::class);
        $configFile->expects($this->atLeastOnce())
            ->method('getFileName')
            ->willReturn('not_exists');

        $this->expectException(GossException::class);
        $this->validator->validate($configFile);
    }

    public function testValidate(): void
    {
        $this->configFile->expects($this->any())
            ->method('getFileName')
            ->willReturn(__FILE__);

        $this->process->expects($this->once())
            ->method('run')
            ->willReturn(0)
        ;

        $this->reportFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->report);

        $this->validator->validate($this->configFile);
    }

    public function testWithNonZeroExitCode(): void
    {
        $this->process->expects($this->once())
            ->method('run')
            ->willReturn(255)
        ;

        $this->expectException(GossException::class);
        $this->validator->validate($this->configFile);
    }

    public function testWhenFailedToCreateReport(): void
    {
        $this->process->expects($this->once())
            ->method('run')
            ->willReturn(0);

        $this->reportFactory->expects($this->once())
            ->method('create')
            ->willThrowException(new \Exception('some error'));

        $this->expectException(GossException::class);

        $this->validator->validate($this->configFile);
    }
}
