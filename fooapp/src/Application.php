<?php

namespace Tkotosz\FooApp;

use ReflectionClass;
use Throwable;
use Tkotosz\FooApp\ConsoleExtension\ConsoleExtension;

class Application
{
    /** @var array */
    private $extensions;

    /** @var callable */
    private $externalCommandsCallback;

    public function __construct(array $extensions, callable $externalCommandsCallback)
    {
        $this->extensions = $extensions;
        $this->externalCommandsCallback = $externalCommandsCallback;
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
                $extension->load($consoleExtension->getApplication());
            }
        }

        $app = $consoleExtension->getApplication();
        $app->add(new ExtensionInstallCommandProxy($this->externalCommandsCallback));
        $app->run();
    }

    private function createExtensions(): array
    {
        return array_filter(array_map(function ($className) {
            try {
                return new $className;
            } catch (Throwable $e) {
                return null;
            }
        }, $this->extensions));
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
