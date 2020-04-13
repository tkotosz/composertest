<?php

namespace Tkotosz\FooApp\FooExtension\Command;

use Tkotosz\FooApp\ExtensionApi\Command;
use Tkotosz\FooApp\ExtensionApi\CommandDefinition;
use Tkotosz\FooApp\ExtensionApi\CommandHandlerServiceId;
use Tkotosz\FooApp\FooExtension\CommandHandler\FooCommandHandler;

class FooCommand implements Command
{
    public function getDefinition(): CommandDefinition
    {
        return new CommandDefinition('foo:run', 'Fooooooo');
    }

    public function getHandlerServiceId(): CommandHandlerServiceId
    {
        return new CommandHandlerServiceId(FooCommandHandler::class);
    }
}