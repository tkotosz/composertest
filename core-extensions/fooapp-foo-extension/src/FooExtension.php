<?php

namespace Tkotosz\FooApp\FooExtension;

use Tkotosz\FooApp\ExtensionApi\Command;
use Tkotosz\FooApp\ExtensionApi\CommandRegistry;
use Tkotosz\FooApp\ExtensionApi\Extension;

class FooExtension implements Extension
{
    public function initialize(): void
    {
        // TODO: Implement initialize() method.
    }

    public function configure(): void
    {
        // TODO: Implement configure() method.
    }

    public function load(CommandRegistry $commandRegistry): void
    {
        $commandRegistry->add(new Command('foo:run'));
    }
}