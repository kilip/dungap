<?php

namespace Dungap\Contracts\Service;

interface ValidatorInterface
{
    public function validate(): ValidatorReportInterface;
}
