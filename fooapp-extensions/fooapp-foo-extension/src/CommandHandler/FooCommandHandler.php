<?php

namespace Tkotosz\FooApp\FooExtension\CommandHandler;

use Tkotosz\FooApp\ExtensionApi\CommandHandler;
use Tkotosz\FooApp\ExtensionApi\CommandInput;
use Tkotosz\FooApp\ExtensionApi\CommandOutput;

class FooCommandHandler implements CommandHandler
{
    public function execute(CommandInput $input, CommandOutput $output): int
    {
        $output->writeln('Hello world!');

        return 0;
    }
}