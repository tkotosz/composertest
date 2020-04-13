<?php

namespace Tkotosz\FooApp\Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tkotosz\FooApp\DependencyInjection\CommandHandlersContainer;
use Tkotosz\FooApp\ExtensionApi\Command;

class SymfonyCommandAdapter extends SymfonyCommand
{
    /** @var Command */
    private $command;

    /** @var CommandHandlersContainer */
    private $commandsContainer;

    public function __construct(Command $command, CommandHandlersContainer $commandsContainer)
    {
        $this->command = $command;
        $this->commandsContainer = $commandsContainer;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName($this->command->getDefinition()->name())
            ->setDescription($this->command->getDefinition()->description())
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return $this->commandsContainer
            ->getHandler($this->command->getHandlerServiceId())
            ->execute(new CommandInput($input), new CommandOutput($output));
    }
}