<?php

namespace Tkotosz\FooApp\Console;

use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Tkotosz\FooApp\DependencyInjection\CommandHandlersContainer;
use Tkotosz\FooApp\ExtensionApi\CommandProviderInterface;

class SymfonyCommandLoader implements CommandLoaderInterface
{
    /** @var CommandProviderInterface */
    private $commandProvider;

    /** @var CommandHandlersContainer */
    private $commandHandlersContainer;

    public function __construct(
        CommandProviderInterface $commandProvider,
        CommandHandlersContainer $commandHandlersContainer
    ) {
        $this->commandProvider = $commandProvider;
        $this->commandHandlersContainer = $commandHandlersContainer;
    }

    public function get(string $name)
    {
        return $this->load()[$name] ?? null;
    }

    public function has(string $name)
    {
        return isset($this->load()[$name]);
    }

    public function getNames()
    {
        return array_keys($this->load());
    }

    private function load()
    {
        $commands = [];

        foreach ($this->commandProvider->getCommands() as $command) {
            $commands[$command->getDefinition()->name()] = new SymfonyCommandAdapter($command, $this->commandHandlersContainer);
        }

        return $commands;
    }
}