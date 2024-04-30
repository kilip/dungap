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

use Dungap\Setting\Command\NewConfigurationCommand;
use Dungap\Setting\Config\Definition;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Yaml\Yaml;

class Config
{
    private ConfigCache $cache;

    /**
     * @var array<string,mixed>
     */
    private array $configs = [];

    /**
     * @param string $configDirs A comma separated list of configuration directories
     */
    public function __construct(
        #[Autowire('%kernel.cache_dir%/dungap')]
        string $cachePath,
        #[Autowire('%env(DUNGAP_CONFIG_DIRS)%')]
        private readonly string $configDirs,
        private MessageBusInterface $messageBus,
        #[Autowire('%kernel.debug%')]
        bool $debug = false,
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

        include $cache->getPath();

        if (!$fresh) {
            $this->messageBus->dispatch(new NewConfigurationCommand($this->configs));
        }
    }

    /**
     * @param array<int,mixed> $configs
     */
    private function createCacheContent(array $configs): string
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Definition(), $configs);

        return "<?php\n\$this->configs = ".var_export($config, true).';';
    }

    /**
     * @return array<string,mixed>
     */
    public function getAll(): array
    {
        return $this->configs;
    }

    /**
     * @return array<string,mixed>
     */
    public function getDevices(): array
    {
        return $this->configs['devices'];
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
}
