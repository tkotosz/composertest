<?php

namespace Tkotosz\CliAppWrapper\Composer;

class ComposerConfig
{
    /** @var string */
    private $appDir;

    /** @var array */
    private $rootRequirements;

    /** @var array */
    private $repositories;

    public function __construct(string $appDir, array $rootRequirements, array $repositories)
    {
        $this->appDir = $appDir;
        $this->rootRequirements = $rootRequirements;
        $this->repositories = $repositories;
    }

    public function toJson(): string
    {
        $config = [
            'repositories' => $this->repositories,
            'minimum-stability' => 'dev',
            'prefer-stable' => true,
            'config' => ['vendor-dir' => $this->appDir],
            'require' => $this->rootRequirements,
            'provide' => [
                'tkotosz/cli-app-wrapper-api' => '*'
            ]
        ];

        if (empty($config['require'])) {
            unset($config['require']);
        }

        return json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function appDir(): string
    {
        return $this->appDir;
    }

    public function rootRequirements(): array
    {
        return $this->rootRequirements;
    }

    public function composerJsonFile(): string
    {
        return $this->appDir . '/composer-config.json';
    }

    public function installedExtensionsFile(): string
    {
        return $this->appDir . '/extensions.php';
    }
}