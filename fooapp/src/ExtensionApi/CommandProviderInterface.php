<?php

namespace Tkotosz\FooApp\ExtensionApi;

interface CommandProviderInterface
{
    /**
     * @return Command
     */
    public function getCommands(): array;
}