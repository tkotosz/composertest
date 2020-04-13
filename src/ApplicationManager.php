<?php

namespace Tkotosz\CliAppWrapper;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use Tkotosz\CliAppWrapper\Composer\Composer;
use Tkotosz\CliAppWrapper\Config\WrappedAppConfig;
use Tkotosz\CliAppWrapperApi\ApplicationManager as ApplicationManagerInterface;

class ApplicationManager implements ApplicationManagerInterface
{
    /** @var WrappedAppConfig */
    private $config;

    public function __construct(WrappedAppConfig $config)
    {
        $this->config = $config;
    }

    public function installExtension(string $extension): int
    {
        $fileSystem = new Filesystem(new Local('./'));

        $composerConfig = $this->config->toComposerConfig();

        $composer = new Composer($composerConfig);

        $conf = [];
        if ($fileSystem->has($this->config->appConfigFile())) {
            $conf = Yaml::parse($fileSystem->read($this->config->appConfigFile()));
        }
        $composerJsonContentOrig = $composerJsonContent = json_decode($fileSystem->read($composerConfig->composerJsonFile()), true);

        if (!empty($extension)) {

            $found = false;
            foreach ($conf['extensions'] ?? [] as $extension) {
                if ($extension['name'] == $extension) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $conf['extensions'][] = [
                    'name' => $extension,
                    'version' => '*'
                ];
            }
        }

        $composerJsonContent['require'] = $composerConfig->rootRequirements();

        foreach ($conf['extensions'] ?? [] as $extension) {
            $composerJsonContent['require'][$extension['name']] = $extension['version'];
        }

        $fileSystem->put($composerConfig->composerJsonFile(), json_encode($composerJsonContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $status = $composer->install();

        if ($status !== 0) {
            $fileSystem->put($composerConfig->composerJsonFile(), json_encode($composerJsonContentOrig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            return $status;
        }

        $fileSystem->put($this->config->appConfigFile(), Yaml::dump($conf));

        return 0;
    }

    public function listExtensions(): array
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

        $list = [];
        foreach ($availableExtensions as $extension => $latestVersion) {
            $list[] = [
                'Name' => $extension,
                'Installed' => isset($installedExtensions[$extension]) ? 'Yes' : 'No',
                'Latest Version' => $latestVersion,
                'Installed Version' => isset($installedExtensions[$extension]) ? $installedExtensions[$extension] : 'None'
            ];
        }

        return $list;
    }

    public function removeExtension(string $extension): int
    {
        return 0;
//        $fileSystem = new Filesystem(new Local('./'));
//
//        $config = json_decode($fileSystem->read('.fooapp/composer-config.json'), true);
//        unset($config['require'][$input->getArgument('extension')]);
//        if (empty($config['require'])) {
//            unset($config['require']);
//        }
//        $fileSystem->put('.fooapp/composer-config.json', json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
//
//        $composerIo = new ConsoleIO($input, $output, $this->getHelperSet());
//        $customComposer = Factory::create($composerIo, '.fooapp/composer-config.json', true);
//        $installer = Installer::create($composerIo, $customComposer);
//
//        $installer
//            ->setUpdate(true)
//            ->setWriteLock(false)
//            ->setDevMode(false)
//            ->setPreferStable(true)
//            ->run();
//
//        $x = require __DIR__ . '/../../../.fooapp/extensions.php';
//        foreach ($customComposer->getRepositoryManager()->getLocalRepository()->getPackages() as $package) {
//            if ($package->getType() === 'fooapp-extension') {
//                $x[] = $package->getExtra()['fooapp-extension-class'];
//            }
//        }
//
//        $x = array_unique($x);
//        $x = array_map(function ($y) { return "    '$y'";}, $x);
//        $x = implode(",\n", $x);
//        $x = "<?php\n\nreturn [\n" . $x . "\n];";
//
//        $fileSystem->put('.fooapp/extensions.php', $x);
//
//        return 0;
    }
}