<?php

namespace Tkotosz\CliAppWrapper;

use Tkotosz\CliAppWrapperApi\ApplicationConfig;
use Tkotosz\CliAppWrapperApi\Application;
use Tkotosz\CliAppWrapperApi\ApplicationFactory;

class CliAppWrapper
{
    public function createWrappedApplication(ApplicationConfig $config): Application
    {
        try {
            $workingDir = $this->getWorkingDir($config);

            if ($workingDir === null) {
                return new AppInitApplication($config);
            }

            $this->autoloadWrappedApplication($config, $workingDir);

            return $this->createApplication($config, $workingDir);
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            exit(255);
        }
    }

    private function createApplication(ApplicationConfig $config, string $workingDir): Application
    {
        $appFactory = $config->appFactory();

        if (!class_exists($appFactory)) {
            throw new \RuntimeException(
                sprintf('Application Factory class "%s" not found', $appFactory)
            );
        }

        if (!is_subclass_of($appFactory, ApplicationFactory::class)) {
            throw new \RuntimeException(
                sprintf('Application Factory "%s" must implement "%s"', $appFactory, ApplicationFactory::class)
            );
        }

        return $appFactory::create(new ApplicationManager($config, $workingDir));
    }

    private function autoloadWrappedApplication(ApplicationConfig $config, string $workingDir): void
    {
        require $workingDir . '/' . $config->appDir() . '/autoload.php';
    }

    private function getWorkingDir(ApplicationConfig $config): ?string
    {
        $local = getcwd();
        $global = $this->getHomeDir();

        if (file_exists($local . '/' . $config->appDir() . '/autoload.php')) {
            return $local;
        }

        if (file_exists($global . '/' . $config->appDir() . '/autoload.php')) {
            return $global;
        }

        return null;
    }

    private function getHomeDir(): string
    {
        if ($path = getenv('XDG_CONFIG_HOME')) {
            return $path;
        }

        return getenv('HOME') ?: (getenv('HOMEDRIVE') . DIRECTORY_SEPARATOR . getenv('HOMEPATH'));
    }
}