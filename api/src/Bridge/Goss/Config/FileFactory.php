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
use Dungap\Bridge\Goss\Contracts\GossConfigRepositoryInterface;
use Dungap\Bridge\Goss\Contracts\GossFileFactoryInterface;
use Dungap\Bridge\Goss\Contracts\GossFileInterface;
use Dungap\Bridge\Goss\Contracts\GossConfigInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Yaml\Dumper;

class FileFactory implements GossFileFactoryInterface
{

    public function __construct(
        private GossConfigRepositoryInterface $configRepository,
        #[Autowire('%dungap.goss.config.dir%')]
        private string $targetDir,

    ) {
    }

    public function create(array $configs, string $fileName): GossFileInterface
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

        $target = "{$this->targetDir}/{$fileName}";
        $configFile = new GossFile($target);
        $configFile->write($yaml);

        return $configFile;
    }

    public function configure(): void
    {
        $all = $this->configRepository->findAll();
        $this->create($all, Constant::GossFileName);
    }

    public function getFile(): GossFileInterface
    {
        $filename = Constant::GossFileName;
        return new GossFile("{$this->targetDir}/{$filename}");
    }


    private function process(Config $config, GossConfigInterface $item): void
    {
        $service = $item->getService();
        $device = $service->getDevice();
        $port = $service->getPort();
        $ip = $device->getIpAddress();
        $serviceId = $item->getService()->getId();

        if (Constant::ValidatorTypeAddress == $item->getType()) {
            $address = "tcp://{$ip}:{$port}";
            $id = $serviceId ?? $address;
            $addr = new Addr(
                $address,
            );
            $config->addAddr($id, $addr);
        }
    }
}
