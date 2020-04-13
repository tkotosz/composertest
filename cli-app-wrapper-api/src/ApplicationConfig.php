<?php

namespace Tkotosz\CliAppWrapperApi;

class ApplicationConfig
{
    /** @var array */
    private $config;

    public static function fromArray(array $config): self
    {
        // TODO validation
        return new self($config);
    }

    public function appName(): string
    {
        return $this->config['app_name'];
    }

    public function appPackage(): string
    {
        return $this->config['app_package'];
    }

    public function appVersion(): string
    {
        return $this->config['app_version'];
    }

    public function appDir(): string
    {
        return $this->config['app_dir'];
    }

    public function appFactory(): string
    {
        return $this->config['app_factory'];
    }

    public function userConfigFile(): string
    {
        return $this->config['user_config_file'];
    }

    public function repositories(): array
    {
        return $this->config['repositories'];
    }

    private function __construct(array $config)
    {
        $this->config = $config;
    }
}