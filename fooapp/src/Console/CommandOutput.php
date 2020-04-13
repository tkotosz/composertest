<?php

namespace Tkotosz\FooApp\Console;

use Symfony\Component\Console\Output\OutputInterface;
use Tkotosz\FooApp\ExtensionApi\CommandOutput as CommandOutputInterface;

class CommandOutput implements CommandOutputInterface
{
    /** @var OutputInterface */
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function writeln(string $text)
    {
        $this->output->writeln($text);
    }
}