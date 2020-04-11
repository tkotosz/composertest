<?php

namespace Tkotosz\FooApp\ExtensionInstallerExtension;

use Symfony\Component\Console\Application;
use Tkotosz\FooApp\ApplicationConfig;
use Tkotosz\FooApp\ExtensionInstallerExtension\Console\Command\ExtensionInstallCommand;
use Tkotosz\FooApp\ExtensionInstallerExtension\Console\Command\ExtensionListCommand;
use Tkotosz\FooApp\ExtensionInstallerExtension\Console\Command\ExtensionRemoveCommand;

class ExtensionInstallerExtension
{
    public function initialize()
    {
    }

    public function configure()
    {
        // update config tree
    }

    public function load(ApplicationConfig $applicationConfig, Application $application)
    {
        $application->add(new ExtensionInstallCommand($applicationConfig));
        $application->add(new ExtensionRemoveCommand());
        $application->add(new ExtensionListCommand());
    }
}