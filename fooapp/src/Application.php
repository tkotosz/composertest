<?php

namespace Tkotosz\FooApp;

use ReflectionClass;
use Throwable;
use Tkotosz\FooApp\ConsoleExtension\ConsoleExtension;

class Application
{
    /** @var ApplicationConfig */
    private $applicationConfig;

    public function __construct(ApplicationConfig $applicationConfig)
    {
        $this->applicationConfig = $applicationConfig;
    }

    public function run(): void
    {
        $extensions = $this->createExtensions();

        $consoleExtension = $this->findConsoleExtension($extensions);

        foreach ($extensions as $extension) {
            if ((new ReflectionClass($extension))->hasMethod('initialize')) {
                $extension->initialize();
            }
        }

        foreach ($extensions as $extension) {
            if ((new ReflectionClass($extension))->hasMethod('configure')) {
                $extension->configure();
            }
        }

        foreach ($extensions as $extension) {
            if ((new ReflectionClass($extension))->hasMethod('load')) {
                $extension->load($this->applicationConfig, $consoleExtension->getApplication());
            }
        }

        $consoleExtension
            ->getApplication()
            ->run();
    }

    private function createExtensions(): array
    {
        return array_filter(array_map(function ($className) {
            try {
                return new $className;
            } catch (Throwable $e) {
                return null;
            }
        }, $this->applicationConfig->extensions()));
    }

    private function findConsoleExtension($extensions): ConsoleExtension
    {
        foreach ($extensions as $extension) {
            if ($extension instanceof \Tkotosz\FooApp\ConsoleExtension\ConsoleExtension) {
                return $extension;
            }
        }

        throw new \RuntimeException('something went wrong');
    }
}