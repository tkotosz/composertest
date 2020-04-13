<?php

namespace Tkotosz\CliAppWrapper\Composer;

use Tkotosz\CliAppWrapperApi\ApplicationConfig;

class ComposerConfig
{
    /** @var string */
    private $vendorDir;

    /** @var array */
    private $rootRequirements;

    /** @var array */
    private $provide;

    /** @var array */
    private $repositories;

    public static function fromValues(string $vendorDir, array $rootRequirements, array $provide, array $repositories): self
    {
        return new self($vendorDir, $rootRequirements, $provide, $repositories);
    }

    public static function fromConfigAndWorkingDir(ApplicationConfig $config, string $workingDir): self
    {
        return self::fromValues(
            $workingDir . '/' . $config->appDir(),
            [$config->appPackage() => $config->appVersion()],
            ['tkotosz/cli-app-wrapper-api' => '*'],
            $config->repositories()
        );
    }

    public static function fromComposerJsonArray(array $data): self
    {
        return new self($data['config']['vendor-dir'], $data['require'], $data['provide'], $data['repositories']);
    }

    public function vendorDir(): string
    {
        return $this->vendorDir;
    }

    public function rootRequirements()
    {
        return $this->rootRequirements;
    }

    public function composerJsonFile(): string
    {
        return $this->vendorDir . '/composer-config.json';
    }

    public function installedExtensionsFile(): string
    {
        return $this->vendorDir . '/extensions.php';
    }

    public function addRequiredExtensions(array $extensions): self
    {
        $require = $this->rootRequirements;

        foreach ($extensions as $extension) {
            $require[$extension['name']] = $extension['version'];
        }

        return ComposerConfig::fromComposerJsonArray(['require' => $require] + $this->toArray());
    }

    public function toArray(): array
    {
        $config = [
            'repositories' => $this->repositories,
            'minimum-stability' => 'dev',
            'prefer-stable' => true,
            'config' => ['vendor-dir' => $this->vendorDir],
            'require' => $this->rootRequirements,
            'provide' => $this->provide
        ];

        if (empty($config['require'])) {
            unset($config['require']);
        }

        return $config;
    }

    public function toComposerJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    private function __construct(string $vendorDir, array $rootRequirements, array $provide, array $repositories)
    {
        $this->vendorDir = $vendorDir;
        $this->rootRequirements = $rootRequirements;
        $this->provide = $provide;
        $this->repositories = $repositories;
    }
}