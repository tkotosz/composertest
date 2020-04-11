<?php

namespace Tkotosz\FooApp\ConsoleExtension;

use Symfony\Component\Console\Application;

class ConsoleExtension
{
    /** @var Application */
    private $application;

    public function initialize()
    {
        $this->application = new Application('fooapp', '1.0.0');
        $this->application->setCatchExceptions(true);
    }

    public function getApplication(): Application
    {
        return $this->application;
    }
}