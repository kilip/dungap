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

class GossException extends \Exception
{
    public static function executableFileInvalid(string $executableFile): self
    {
        return new self(sprintf(
            'GOSS executable file "%s" is invalid',
            $executableFile
        ));
    }

    public static function invalidGossConfigFile(string $filename): self
    {
        return new self(sprintf(
            'GOSS config file "%s" is invalid',
            $filename
        ));
    }

    public static function validationError(int $exitCode, string $fileName, string $output): self
    {
        return new self(sprintf(
            'GOSS returns exit code "%s" while executing with config file "%s". Output: "%s"',
            $exitCode,
            $fileName,
            $output
        ));
    }

    public static function createOutputFailed(string $output, string $error): self
    {
        return new self(sprintf(
            "GOSS fail to processing output. Error  \"%s\".",
            $error,
        ));
    }
}
