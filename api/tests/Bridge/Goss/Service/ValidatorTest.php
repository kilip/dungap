<?php

namespace Dungap\Tests\Bridge\Goss\Service;

use Dungap\Bridge\Goss\Config\FileFactory;
use Dungap\Bridge\Goss\Contracts\GossFileFactoryInterface;
use Dungap\Bridge\Goss\Contracts\GossFileInterface;
use Dungap\Bridge\Goss\Contracts\GossInterface;
use Dungap\Bridge\Goss\Contracts\GossReportInterface;
use Dungap\Bridge\Goss\Service\Validator;
use Dungap\Contracts\Service\ValidatorReportInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    private MockObject|GossFileFactoryInterface $fileFactory;
    private MockObject|GossInterface $goss;
    private MockObject|ValidatorReportInterface $report;
    private Validator $validator;

    protected function setUp(): void
    {
        $this->fileFactory = $this->createMock(GossFileFactoryInterface::class);
        $this->goss = $this->createMock(GossInterface::class);
        $this->report = $this->createMock(GossReportInterface::class);

        $this->validator = new Validator(
            $this->fileFactory,
            $this->goss
        );
    }

    public function testValidate()
    {
        $file = $this->createMock(GossFileInterface::class);
        $this->fileFactory->expects($this->once())
            ->method('getFile')
            ->willReturn($file);
        $file->expects($this->once())
            ->method('getFileName')
            ->willReturn(__DIR__.'/not-exist.yaml');

        $this->fileFactory->expects($this->once())
            ->method('configure');

        $this->goss->expects($this->once())
            ->method('validate')
            ->with($file)
            ->willReturn($this->report)
        ;

        $this->validator->validate();
    }
}
