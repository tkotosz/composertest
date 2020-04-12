<?php

namespace Tkotosz\FooApp;

use Tkotosz\FooApp\ExtensionApi\Command;
use Tkotosz\FooApp\ExtensionApi\CommandRegistry as CommandRegistryInterface;

class CommandRegistry implements CommandRegistryInterface
{
    /** @var Command */
    private $foo;

    public function add(Command $command)
    {
        $this->foo= $command;
    }

    public function getFoo(): ?Command
    {
        return $this->foo;
    }
}