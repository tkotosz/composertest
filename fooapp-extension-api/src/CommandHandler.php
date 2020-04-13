<?php

namespace Tkotosz\FooApp\ExtensionApi;

interface CommandHandler
{
    public function execute(CommandInput $input, CommandOutput $output): int;
}