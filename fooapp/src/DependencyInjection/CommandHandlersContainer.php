<?php

namespace Tkotosz\FooApp\DependencyInjection;

use Psr\Container\ContainerInterface;
use Tkotosz\FooApp\ExtensionApi\CommandHandler;
use Tkotosz\FooApp\ExtensionApi\CommandHandlerServiceId;

class CommandHandlersContainer
{
    /** @var ContainerInterface */
    private $commandHandlersContainer;

    public function __construct(ContainerInterface $commandHandlersContainer)
    {
        $this->commandHandlersContainer = $commandHandlersContainer;
    }

    public function getHandler(CommandHandlerServiceId $commandHandlerServiceId): CommandHandler
    {
        return $this->commandHandlersContainer->get($commandHandlerServiceId->serviceId());
    }
}