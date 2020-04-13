<?php

namespace Tkotosz\CliAppWrapper\Composer;

use Composer\Factory;
use Composer\Installer;
use Composer\IO\ConsoleIO;
use Composer\IO\NullIO;
use Composer\Package\PackageInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Composer
{
    /** @var ComposerConfig */
    private $composerConfig;

    public function __construct(ComposerConfig $composerConfig)
    {
        $this->composerConfig = $composerConfig;
    }

    public function init(): InitResult
    {
        $this->createFiles();

        $output = new BufferedOutput();
        $status = $this->install(new ArgvInput(), $output);
        $result = new InitResult($status, $output->fetch());

        if ($result->isError()) {
            $this->removeFiles();
        }

        return $result;
    }

    public function install(?InputInterface $input = null, ?OutputInterface $output = null): int
    {
        $input = $input ?? new ArgvInput();
        $output = $output ?? new BufferedOutput();
        $result = $this->installer($input, $output)
            ->setUpdate(true)
            ->setWriteLock(false)
            ->setDevMode(false)
            ->setPreferStable(true)
            ->run();

        echo $output->fetch();

        $this->updateInstalledExtensionsFile();

        return $result;
    }

    /**
     * @param string $type
     *
     * @return PackageInterface[]
     */
    public function findInstalledPackagesByType(string $type): array
    {
        $packages = [];
        $customComposer = Factory::create(new NullIO(), $this->composerConfig->composerJsonFile(), true);

        foreach ($customComposer->getRepositoryManager()->getLocalRepository()->getPackages() as $package) {
            if ($package->getType() === $type) {
                $packages[] = $package;
            }
        }

        return $packages;
    }

    private function createFiles(): void
    {
        mkdir($this->composerConfig->vendorDir());
        file_put_contents($this->composerConfig->composerJsonFile(), $this->composerConfig->toComposerJson());
        file_put_contents($this->composerConfig->installedExtensionsFile(), "<?php\n\nreturn [\n];\n");
    }

    private function removeFiles(): void
    {
        unlink($this->composerConfig->composerJsonFile());
        unlink($this->composerConfig->installedExtensionsFile());
        rmdir($this->composerConfig->vendorDir());
    }

    private function updateInstalledExtensionsFile(): void
    {
        $extensions = [];
        foreach ($this->findInstalledPackagesByType('fooapp-extension') as $package) {
            $extensions[] = $package->getExtra()['fooapp-extension-class'];
        }

        $extensions = array_unique($extensions);
        $extensions = array_map(function ($y) { return "    '$y'";}, $extensions);
        $extensions = implode(",\n", $extensions);
        $extensions = "<?php\n\nreturn [\n" . $extensions . "\n];";

        file_put_contents($this->composerConfig->installedExtensionsFile(), $extensions);
    }

    private function installer(InputInterface $input, OutputInterface $output): Installer
    {
        $composerIo = new ConsoleIO($input, $output, new HelperSet([new QuestionHelper()]));
        $customComposer = Factory::create($composerIo, $this->composerConfig->composerJsonFile(), true);

        return Installer::create($composerIo, $customComposer);
    }
}