<?php

namespace Tkotosz\CliAppWrapper\Service;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use Tkotosz\CliAppWrapperApi\ApplicationConfig;
use Tkotosz\CliAppWrapper\Config\WrappedAppConfig;

class WrappedAppConfigManager
{
    /** @var ApplicationConfig */
    private $config;

    public function __construct(ApplicationConfig $config)
    {
        $this->config = $config;
    }

    public function load(string $rootDir, string $file): WrappedAppConfig
    {
        $fileSystem = new Filesystem(new Local($rootDir));

        $config = [];
        if ($fileSystem->has($this->config->userConfigFile())) {
            $config = Yaml::parse($fileSystem->read($this->config->userConfigFile())) ?? [];
        }

        return WrappedAppConfig::fromArray($config);
    }

    public function save(string $rootDir, string $file, WrappedAppConfig $config): void
    {
        $fileSystem = new Filesystem(new Local($rootDir));

        $data = $config->toArray();

        if ($data['extensions'] === []) {
            unset($data['extensions']);
        }

        if (empty($data)) {
            $content = '';
        } else {
            $content = Yaml::dump($data);
        }

        $fileSystem->put($this->config->userConfigFile(), $content);
    }
}