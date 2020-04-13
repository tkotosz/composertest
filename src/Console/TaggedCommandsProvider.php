<?php

namespace Tkotosz\FooApp\Console;

use Tkotosz\FooApp\ExtensionApi\CommandProviderInterface;

class TaggedCommandsProvider implements CommandProviderInterface
{
    /** @var array */
    private $commands;

    public function __construct(array $commands)
    {
        $this->commands = $commands;
    }

    public function getCommands(): array
    {
        return $this->commands;
    }
}
