<?php

namespace Tkotosz\FooApp\Console;

use Tkotosz\FooApp\ExtensionApi\CommandProviderInterface;

class CompositeCommandsProvider implements CommandProviderInterface
{
    /** @var array */
    private $commandProviders;

    public function __construct(array $commandProviders)
    {
        $this->commandProviders = $commandProviders;
    }

    public function getCommands(): array
    {
        $commands = [];

        foreach ($this->commandProviders as $commandProvider) {
            foreach ($commandProvider->getCommands() as $command) {
                $commands[] = $command;
            }
        }

        return $commands;
    }
}