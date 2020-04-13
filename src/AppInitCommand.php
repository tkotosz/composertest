<?php

namespace Tkotosz\CliAppWrapper;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tkotosz\CliAppWrapper\Composer\Composer;
use Tkotosz\CliAppWrapper\Composer\ComposerConfig;
use Tkotosz\CliAppWrapperApi\ApplicationConfig;

class AppInitCommand extends Command
{
    /** @var ApplicationConfig */
    private $config;

    public function __construct(ApplicationConfig $config)
    {
        $this->config = $config;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Initialize the application working directory')
            ->addArgument('mode', InputArgument::OPTIONAL, 'Working mode: local or global')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mode = $input->getArgument('mode');

        if (empty($mode) && file_exists(getcwd() . '/' . $this->config->userConfigFile())) {
            $mode = 'local';
        }

        if (empty($mode)) {
            throw new \Exception('Please specify the mode: local or global');
        }

        $workingDir = ($mode === 'local') ? getcwd() : $this->getHomeDir();

        $result = (new Composer(ComposerConfig::fromConfigAndWorkingDir($this->config, $workingDir)))->init();

        if ($result->isError()) {
            $output->writeln($result->output());
        }

        return $result->status();
    }

    private function getHomeDir(): string
    {
        if ($path = getenv('XDG_CONFIG_HOME')) {
            return $path;
        }

        return getenv('HOME') ?: (getenv('HOMEDRIVE') . DIRECTORY_SEPARATOR . getenv('HOMEPATH'));
    }
}