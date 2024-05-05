<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\RouterOS\Contracts;

use Symfony\Contracts\HttpClient\HttpClientInterface;

interface HttpClientFactoryInterface
{
    public function create(): HttpCLientInterface;
}
