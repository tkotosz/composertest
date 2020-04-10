<?php

namespace Tkotosz\FooApp\Console\Command;

use Composer\Factory;
use Composer\Installer;
use Composer\IO\ConsoleIO;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExtensionInstallCommand extends Command
{
    protected function configure()
    {
        $this->setName('extension:install');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $composerIo = new ConsoleIO($input, $output, $this->getHelperSet());
        $customComposer = Factory::create($composerIo, 'custom-composer.json', true);
        $installer = Installer::create($composerIo, $customComposer);

        $installer
            ->setUpdate(true)
            ->setWriteLock(false)
            ->setDevMode(false)
            ->setPreferStable(true)
            ->run();

        return 0;
    }
}