<?php

namespace Dungap\Bridge\Goss\Service;

use Dungap\Bridge\Goss\Contracts\GossFileFactoryInterface;
use Dungap\Bridge\Goss\Contracts\GossInterface;
use Dungap\Contracts\Service\ValidatorInterface;
use Dungap\Contracts\Service\ValidatorReportInterface;

class Validator implements ValidatorInterface
{
    public function __construct(
        private GossFileFactoryInterface $fileFactory,
        private GossInterface $goss
    )
    {

    }

    public function validate(): ValidatorReportInterface
    {
        $file = $this->fileFactory->getFile();

        if(!file_exists($file->getFileName())) {
            $this->fileFactory->configure();
        }

        return $this->goss->validate($file);
    }

}
