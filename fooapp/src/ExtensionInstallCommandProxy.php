<?php

namespace Tkotosz\FooApp;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExtensionInstallCommandProxy extends Command
{
    /** @var callable */
    private $callback;

    public function __construct(callable $callback)
    {
        parent::__construct();
        $this->callback = $callback;
    }

    protected function configure()
    {
        $this->setName('extension:install')
            ->addArgument('extension', InputArgument::OPTIONAL, 'Extension to install');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ($this->callback)();
    }
}