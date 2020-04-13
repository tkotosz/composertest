<?php

namespace Tkotosz\CliAppWrapper;

use Symfony\Component\Yaml\Yaml;
use Tkotosz\CliAppWrapper\Composer\Composer;
use Tkotosz\CliAppWrapper\Composer\ComposerConfig;
use Tkotosz\CliAppWrapperApi\ApplicationConfig;
use Tkotosz\CliAppWrapper\Service\WrappedAppComposerJsonManager;
use Tkotosz\CliAppWrapper\Service\WrappedAppConfigManager;
use Tkotosz\CliAppWrapperApi\ApplicationManager as ApplicationManagerInterface;

class ApplicationManager implements ApplicationManagerInterface
{
    /** @var ApplicationConfig */
    private $config;

    /** @var string */
    private $workingDir;

    public function __construct(ApplicationConfig $config, string $workingDir)
    {
        $this->config = $config;
        $this->workingDir = $workingDir;
    }

    public function installExtension(string $extension): int
    {
        $wrappedAppConfigManager = new WrappedAppConfigManager($this->config);
        $wrappedAppComposerJsonManager = new WrappedAppComposerJsonManager($this->config);

        // get the default requirements
        $defaultComposerConfig = ComposerConfig::fromConfigAndWorkingDir($this->config, $this->workingDir);
        $composer = new Composer($defaultComposerConfig);
        // get the current composer config
        $currentComposerConfig = $wrappedAppComposerJsonManager->load($this->workingDir);
        // get the currently required extensions
        $currentAppConfig = $wrappedAppConfigManager->load($this->workingDir, $this->config->userConfigFile());
        // add newly required extension if provided
        if ($extension) $currentAppConfig->addExtension($extension);
        // build new composer config from default with the required extensions
        $newComposerConfig = $defaultComposerConfig->addRequiredExtensions($currentAppConfig->extensions());
        // save the new composer config
        $wrappedAppComposerJsonManager->save($this->workingDir, $newComposerConfig);
        // try to install
        $status = $composer->install();

        if ($status !== 0) {
            // restore previous state if failed on install
            $wrappedAppComposerJsonManager->save($this->workingDir, $currentComposerConfig);

            return $status;
        }

        // update the app config
        $wrappedAppConfigManager->save($this->workingDir, $this->config->userConfigFile(), $currentAppConfig);

        return 0;
    }

    public function removeExtension(string $extension): int
    {
        $wrappedAppConfigManager = new WrappedAppConfigManager($this->config);
        $currentAppConfig = $wrappedAppConfigManager->load($this->workingDir, $this->config->userConfigFile());
        $currentAppConfig->removeExtension($extension);
        $wrappedAppConfigManager->save($this->workingDir, $this->config->userConfigFile(), $currentAppConfig);

        return $this->installExtension('');
    }

    public function getApplicationConfig(): ApplicationConfig
    {
        return $this->config;
    }

    public function getWorkingDirectory(): string
    {
        return $this->workingDir;
    }

    public function findInstalledExtensionClasses(): array
    {
        return (require $this->workingDir . '/' . $this->config->appDir() . '/extensions.php');
    }

    public function findInstalledExtensions(): array
    {
        $wrappedAppComposerJsonManager = new WrappedAppComposerJsonManager($this->config);

        // TODO filter main app :)
        return $wrappedAppComposerJsonManager->load($this->workingDir)->rootRequirements();
    }

    public function findAvailableExtensions(): array
    {
        // get from packagist based on package type?
        return $availableExtensions = [
            'tkotosz/fooapp-foo-extension' => '1.0.0',
            'tkotosz/fooapp-bar-extension' => '1.0.0',
            'tkotosz/fooapp-baz-extension' => '1.0.0',
        ];
    }

    public function getUserConfig(): array
    {
        $userConfigPath = $this->workingDir . '/' . $this->config->userConfigFile();

        if (!file_exists($userConfigPath)) {
            return [];
        }

        return Yaml::parse(file_get_contents($userConfigPath));
    }
}