<?php

namespace Tkotosz\CliAppWrapper\Config;

use Tkotosz\CliAppWrapper\Composer\ComposerConfig;

class WrappedAppConfig
{
    /** @var array */
    private $config;

    public static function fromArray(array $config): self
    {
        // TODO validation
        return new self($config);
    }

    public function toComposerConfig(): ComposerConfig
    {
        return new ComposerConfig(
            $this->config['app_dir'],
            [$this->config['app_package'] => $this->config['app_version']],
            $this->config['repositories']
        );
    }

    public function appDir(): string
    {
        return $this->config['app_dir'];
    }

    public function appConfigFile(): string
    {
        return $this->config['app_config_file'];
    }

    public function appFactory(): string
    {
        return $this->config['app_factory'];
    }

    private function __construct(array $config)
    {
        $this->config = $config;
    }
}