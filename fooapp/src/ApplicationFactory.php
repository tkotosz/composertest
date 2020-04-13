<?php

namespace Tkotosz\FooApp;

use Tkotosz\CliAppWrapperApi\Application as ApplicationInterface;
use Tkotosz\CliAppWrapperApi\ApplicationManager;
use Tkotosz\CliAppWrapperApi\ApplicationFactory as ApplicationFactoryInterface;

class ApplicationFactory implements ApplicationFactoryInterface
{
    public static function create(array $extensions, ApplicationManager $applicationManager): ApplicationInterface
    {
        return new Application($extensions, $applicationManager);
    }
}