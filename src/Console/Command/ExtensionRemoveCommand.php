<?php

namespace Tkotosz\FooAppCli\Console\Command;

use Composer\Factory;
use Composer\Installer;
use Composer\IO\ConsoleIO;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExtensionRemoveCommand extends Command
{
    protected function configure()
    {
        $this->setName('extension:remove')
            ->addArgument('extension', InputArgument::REQUIRED, 'Extension to remove');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileSystem = new Filesystem(new Local('./'));

        $config = json_decode($fileSystem->read('.fooapp/composer-config.json'), true);
        unset($config['require'][$input->getArgument('extension')]);
        if (empty($config['require'])) {
            unset($config['require']);
        }
        $fileSystem->put('.fooapp/composer-config.json', json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $composerIo = new ConsoleIO($input, $output, $this->getHelperSet());
        $customComposer = Factory::create($composerIo, '.fooapp/composer-config.json', true);
        $installer = Installer::create($composerIo, $customComposer);

        $installer
            ->setUpdate(true)
            ->setWriteLock(false)
            ->setDevMode(false)
            ->setPreferStable(true)
            ->run();

        $x = require __DIR__ . '/../../../.fooapp/extensions.php';
        foreach ($customComposer->getRepositoryManager()->getLocalRepository()->getPackages() as $package) {
            if ($package->getType() === 'fooapp-extension') {
                $x[] = $package->getExtra()['fooapp-extension-class'];
            }
        }

        $x = array_unique($x);
        $x = array_map(function ($y) { return "    '$y'";}, $x);
        $x = implode(",\n", $x);
        $x = "<?php\n\nreturn [\n" . $x . "\n];";

        $fileSystem->put('.fooapp/extensions.php', $x);

        return 0;
    }
}