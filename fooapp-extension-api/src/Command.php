<?php

namespace Tkotosz\FooApp\ExtensionApi;

interface Command
{
    public function getDefinition(): CommandDefinition;

    public function getHandlerServiceId(): CommandHandlerServiceId;
}