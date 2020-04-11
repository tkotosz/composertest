<?php

namespace Tkotosz\FooApp\FooExtension;

use Symfony\Component\Console\Application;
use Tkotosz\FooApp\ApplicationConfig;
use Tkotosz\FooApp\FooExtension\Console\Command\FooCommand;

class FooExtension
{
    public function initialize()
    {

    }

    public function load(ApplicationConfig $applicationConfig, Application $application)
    {
        $application->add(new FooCommand());
    }
}