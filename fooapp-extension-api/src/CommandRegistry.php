<?php

namespace Tkotosz\FooApp\ExtensionApi;

interface CommandRegistry
{
    public function add(Command $command);
}