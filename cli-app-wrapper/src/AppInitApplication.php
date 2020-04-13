<?php

namespace Tkotosz\CliAppWrapper;

use Tkotosz\CliAppWrapper\Composer\Composer;
use Tkotosz\CliAppWrapper\Composer\ComposerConfig;
use Tkotosz\CliAppWrapperApi\ApplicationConfig;
use Tkotosz\CliAppWrapperApi\Application;

class AppInitApplication implements Application
{
    /** @var ApplicationConfig */
    private $config;

    /** @var string */
    private $workingDir;

    public function __construct(ApplicationConfig $config, string $workingDir)
    {
        $this->config = $config;
        $this->workingDir = $workingDir;
    }

    public function run(): void
    {
        if (!isset($_SERVER['argv'][1]) || $_SERVER['argv'][1] !== 'init') {
            echo "Application is not yet initialized" . PHP_EOL;
            echo "Please run init or local init or global init" . PHP_EOL;
            echo "Help:" . PHP_EOL;
            echo "  init          init locally if app config exists in cwd otherwise globally" . PHP_EOL;
            echo "  local init    init locally" . PHP_EOL;
            echo "  global init   init global" . PHP_EOL;

            exit(0);
        }

        $result = (new Composer(ComposerConfig::fromConfigAndWorkingDir($this->config, $this->workingDir)))->init();

        if ($result->isError()) {
            echo $result->output() . PHP_EOL;
        }

        exit($result->status());
    }
}