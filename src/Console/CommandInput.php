<?php

namespace Tkotosz\FooApp\Console;

use Symfony\Component\Console\Input\InputInterface;
use Tkotosz\FooApp\ExtensionApi\CommandInput as CommandInputInterface;

class CommandInput implements CommandInputInterface
{
    /** @var InputInterface */
    private $input;

    public function __construct(InputInterface $input)
    {
        $this->input = $input;
    }
}