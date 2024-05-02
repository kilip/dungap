<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Setting;

use Dungap\Contracts\Setting\ConfigFactoryInterface;
use Dungap\Contracts\Setting\ConfigInterface;
use Dungap\Setting\Command\NewConfigurationCommand;
use Dungap\Setting\Config\Config;
use Dungap\Setting\Config\Definition;
use Psr\Log\LoggerInterface;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Yaml\Yaml;

class ConfigFactory implements ConfigFactoryInterface
{
    private ConfigCache $cache;

    private ConfigInterface $config;

    /**
     * @param string $configDirs A comma separated list of configuration directories
     */
    public function __construct(
        #[Autowire('%dungap.cache_dir%')]
        string                               $cachePath,
        #[Autowire('%dungap.config_dirs%')]
        private readonly string              $configDirs,
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $logger,
        #[Autowire('%kernel.debug%')]
        bool                                 $debug = false,
    ) {
        $cachePath = "{$cachePath}/config";
        $this->cache = new ConfigCache($cachePath, $debug);
        $this->checkConfiguration();
    }

    public function checkConfiguration(): void
    {
        $dirs = $this->generateDirs();
        $cache = $this->cache;
        $fresh = $cache->isFresh();

        if (empty($dirs)) {
            $this->config = new Config();
            return;
        }

        if (!$fresh) {
            $resources = [];
            $finder = Finder::create()
                ->in($dirs)
                ->name('*.yml')
                ->name('*.yaml');
            $configs = [];
            foreach ($finder->files() as $file) {
                $configs[] = Yaml::parseFile($file->getRealPath());
                $resources[] = new FileResource($file);
            }
            $content = $this->createCacheContent($configs);

            $cache->write($content, $resources);
        }

        $serialized = file_get_contents($cache->getPath());
        $unserialized = unserialize($serialized);

        $this->config = $unserialized[0];

        if (!$fresh) {
            $this->logger->notice('Reloading Configuration');
            $this->messageBus->dispatch(new NewConfigurationCommand($this->config));
        }
    }

    /**
     * @param array<int,mixed> $configs
     */
    private function createCacheContent(array $configs): string
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Definition(), $configs);
        $json = json_encode($config, JSON_PRETTY_PRINT);

        $normalizer = new ObjectNormalizer(
            classMetadataFactory: new ClassMetadataFactory(new AttributeLoader()),
            nameConverter: new CamelCaseToSnakeCaseNameConverter(),
            propertyAccessor: new PropertyAccessor(),
            propertyTypeExtractor: new ReflectionExtractor(),
        );
        $normalizers = [
            $normalizer,
            new GetSetMethodNormalizer(),
            new ArrayDenormalizer(),
        ];
        $serializer = new Serializer($normalizers, [new JsonEncoder()]);
        $data = $serializer->deserialize($json, Config::class, 'json', [
            'json_decode_associative' => true,
        ]);

        return serialize([$data]);
    }

    /**
     * @return array<int,string>
     */
    private function generateDirs(): array
    {
        $dirs = [];
        $exp = explode(',', $this->configDirs);
        foreach ($exp as $dir) {
            if (is_dir($dir)) {
                $dirs[] = realpath($dir);
            }
        }

        return $dirs;
    }

    public function create(): ConfigInterface
    {
        return $this->config;
    }
}
