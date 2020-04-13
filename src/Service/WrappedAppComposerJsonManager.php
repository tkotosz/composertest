<?php

namespace Tkotosz\CliAppWrapper\Service;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Tkotosz\CliAppWrapper\Composer\ComposerConfig;
use Tkotosz\CliAppWrapperApi\ApplicationConfig;

class WrappedAppComposerJsonManager
{
    /** @var ApplicationConfig */
    private $config;

    public function __construct(ApplicationConfig $config)
    {
        $this->config = $config;
    }

    public function load(string $workingDir): ComposerConfig
    {
        $fileSystem = new Filesystem(new Local('/'));

        $defaultConfig = ComposerConfig::fromConfigAndWorkingDir($this->config, $workingDir);

        return ComposerConfig::fromComposerJsonArray(
            json_decode($fileSystem->read($defaultConfig->composerJsonFile()), true)
        );
    }

    public function save(string $workingDir, ComposerConfig $appComposerJsonNew): void
    {
        $fileSystem = new Filesystem(new Local('/'));

        $defaultConfig = ComposerConfig::fromConfigAndWorkingDir($this->config, $workingDir);

        $fileSystem->put($defaultConfig->composerJsonFile(), $appComposerJsonNew->toComposerJson());
    }
}