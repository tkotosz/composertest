<?php

namespace Tkotosz\FooApp\Console\Command;

use Composer\Factory;
use Composer\Installer;
use Composer\IO\ConsoleIO;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExtensionListCommand extends Command
{
    protected function configure()
    {
        $this->setName('extension:list');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileSystem = new Filesystem(new Local('./'));

        $config = json_decode($fileSystem->read('.fooapp/composer-config.json'), true);

        // get from packagist based on package type?
        $availableExtensions = [
            'tkotosz/fooapp-foo-extension' => '1.0.0',
            'tkotosz/fooapp-bar-extension' => '1.0.0',
            'tkotosz/fooapp-baz-extension' => '1.0.0',
        ];
        $installedExtensions = $config['require'] ?? [];


        $table = new Table($output);
        $table->setHeaders(['Name', 'Installed', 'Latest Version', 'Installed Version']);
        foreach ($availableExtensions as $extension => $latestVersion) {
            $table->addRow(
                [
                    'Name' => $extension,
                    'Installed' => isset($installedExtensions[$extension]) ? 'Yes' : 'No',
                    'Latest Version' => $latestVersion,
                    'Installed Version' => isset($installedExtensions[$extension]) ? $installedExtensions[$extension] : 'None'
                ]
            );
        }

        $table->render();

        return 0;
    }
}