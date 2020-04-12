<?php

namespace Tkotosz\FooApp\FooExtension;

use Symfony\Component\Console\Application;
use Tkotosz\FooApp\FooExtension\Console\Command\FooCommand;

class FooExtension
{
    public function initialize()
    {

    }

    public function load(Application $application)
    {
        $application->add(new FooCommand());
    }
}