<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\Goss\Config;

use Dungap\Bridge\Goss\Constant;
use Dungap\Bridge\Goss\Contracts\GossConfigFactoryInterface;
use Dungap\Bridge\Goss\Contracts\GossConfigFileInterface;
use Dungap\Bridge\Goss\Contracts\GossConfigInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Yaml\Dumper;

class ConfigFactory implements GossConfigFactoryInterface
{
    public function __construct(
        #[Autowire('%kernel.cache_dir%/dungap/goss/config')]
        private string $targetDir,
    ) {
    }

    public function create(array $configs): GossConfigFileInterface
    {
        $config = new Config();
        foreach ($configs as $item) {
            $this->process($config, $item);
        }

        $normalizers = [
            new ObjectNormalizer(
                propertyAccessor: new PropertyAccessor(),
                propertyTypeExtractor: new ReflectionExtractor(),
            ),
        ];
        $encoder = new YamlEncoder(
            dumper: new Dumper(2),
        );
        $serializer = new Serializer($normalizers, [$encoder]);
        $yaml = $serializer->serialize($config, 'yaml', [
            'yaml_inline' => 5,
        ]);

        $fileName = $this->targetDir.'/'.uniqid('config').'.yaml';
        $configFile = new ConfigFile($fileName);
        $configFile->write($yaml);

        return $configFile;
    }

    private function process(Config $config, GossConfigInterface $item): void
    {
        $service = $item->getService();
        $device = $service->getDevice();
        $port = $service->getPort();
        $ip = $device->getIpAddress();

        if (Constant::ValidatorTypeAddress == $item->getType()) {
            $address = "tcp://{$ip}:{$port}";
            $id = $item->getId() ?? $address;
            $addr = new Addr(
                $address,
            );
            $config->addAddr($id, $addr);
        }
    }
}
