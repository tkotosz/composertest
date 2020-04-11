<?php

namespace Tkotosz\FooApp;

class ApplicationConfig
{
    /** @var array */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function appDir(): string
    {
        return $this->config['app_dir'];
    }

    public function rootRequirements(): array
    {
        return $this->config['root_requirements'];
    }

    public function composerRepositories(): array
    {
        return $this->config['composer_repositories'];
    }

    public function extensions(): array
    {
        return $this->config['extensions'] ?? [];
    }
}