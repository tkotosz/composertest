<?php

namespace Tkotosz\FooApp\FooExtension\CommandProvider;

use Tkotosz\FooApp\ExtensionApi\Command;
use Tkotosz\FooApp\ExtensionApi\CommandDefinition;
use Tkotosz\FooApp\ExtensionApi\CommandHandlerServiceId;
use Tkotosz\FooApp\ExtensionApi\CommandProviderInterface;
use Tkotosz\FooApp\FooExtension\CommandHandler\FooCommandHandler;
use Tkotosz\FooApp\FooExtension\Config;

class DynamicCommandsProvider implements CommandProviderInterface
{
    /** @var Config */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getCommands(): array
    {
        // imagine that this is coming from a user provided config
        $config = ['name' => $this->config->getFooCommandAlias(), 'desc' => 'this command is an alias for foo'];

        return [
            new class($config) implements Command {
                /** @var array */
                private $config;

                public function __construct(array $config)
                {
                    $this->config = $config;
                }

                public function getDefinition(): CommandDefinition
                {
                    return new CommandDefinition($this->config['name'], $this->config['desc']);
                }

                public function getHandlerServiceId(): CommandHandlerServiceId
                {
                    return new CommandHandlerServiceId(FooCommandHandler::class);
                }
            }
        ];
    }
}