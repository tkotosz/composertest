<?php

namespace Tkotosz\FooApp;

use ReflectionClass;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use Tkotosz\FooApp\Console\Command\ExtensionInstallCommand;

class Application
{
    /** @var array */
    private $extensions;

    public function __construct(array $extensions)
    {
        $this->extensions = $extensions;
    }

    public function run($composerConfig): void
    {
        $app = new \Symfony\Component\Console\Application();
        $commandRegistry = new CommandRegistry();
        $extensions = $this->createExtensions();

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
                $extension->load($commandRegistry);
            }
        }

        $app->add(new ExtensionInstallCommand($composerConfig));

        $foo = $commandRegistry->getFoo();

        if ($foo) {

            $app->add(new class($foo->name()) extends Command {
                protected function execute(InputInterface $input, OutputInterface $output)
                {
                    $output->writeln('I am running!');

                    return 0;
                }
            });
        }

        $app->run();
    }

    private function createExtensions(): array
    {
        return array_filter(array_map(function ($className) {
            try {
                return new $className;
            } catch (Throwable $e) {
                echo $e->getMessage() . PHP_EOL;
                return null;
            }
        }, $this->extensions));
    }
}
