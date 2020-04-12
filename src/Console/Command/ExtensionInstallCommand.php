<?php

namespace Tkotosz\FooApp\Console\Command;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Tkotosz\FooApp\Composer\Composer;
use Tkotosz\FooApp\Composer\ComposerConfig;

class ExtensionInstallCommand extends Command
{
    /** @var ComposerConfig */
    private $composerConfig;

    public function __construct(ComposerConfig $composerConfig)
    {
        parent::__construct();

        $this->composerConfig = $composerConfig;
    }

    protected function configure()
    {
        $this->setName('extension:install')
            ->addArgument('extension', InputArgument::OPTIONAL, 'Extension to install');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileSystem = new Filesystem(new Local('./'));

        $composerConfig = $this->composerConfig;

        $composer = new Composer($composerConfig);

        $conf = [];
        if ($fileSystem->has('fooapp.yml')) {
            $conf = Yaml::parse($fileSystem->read('fooapp.yml'));
        }
        $composerJsonContentOrig = $composerJsonContent = json_decode($fileSystem->read($composerConfig->composerJsonFile()), true);

        if (!empty($input->getArgument('extension'))) {

            $found = false;
            foreach ($conf['extensions'] ?? [] as $extension) {
                if ($extension['name'] == $input->getArgument('extension')) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $conf['extensions'][] = [
                    'name' => $input->getArgument('extension'),
                    'version' => '*'
                ];
            }
        }

        $composerJsonContent['require'] = $this->composerConfig->rootRequirements();

        foreach ($conf['extensions'] ?? [] as $extension) {
            $composerJsonContent['require'][$extension['name']] = $extension['version'];
        }

        $fileSystem->put($composerConfig->composerJsonFile(), json_encode($composerJsonContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $status = $composer->install($input, $output);

        if ($status !== 0) {
            $fileSystem->put($composerConfig->composerJsonFile(), json_encode($composerJsonContentOrig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            return $status;
        }

        $fileSystem->put('fooapp.yml', Yaml::dump($conf));

        return 0;
    }
}