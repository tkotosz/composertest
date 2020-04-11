<?php

namespace Tkotosz\FooApp\FooExtension\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FooCommand extends Command
{
    protected function configure()
    {
        $this->setName('foo:run');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('foo');

        return 0;
    }
}