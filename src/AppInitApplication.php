<?php

namespace Tkotosz\CliAppWrapper;

use Symfony\Component\Console\Application as SymfonyConsoleApplication;
use Tkotosz\CliAppWrapperApi\ApplicationConfig;
use Tkotosz\CliAppWrapperApi\Application;

class AppInitApplication implements Application
{
    /** @var ApplicationConfig */
    private $config;

    public function __construct(ApplicationConfig $config)
    {
        $this->config = $config;
    }

    public function run(): void
    {
        $app = new SymfonyConsoleApplication($this->config->appName(), $this->config->appVersion());
        $app->add(new AppInitCommand($this->config));
        $app->get('list')->setHidden(true);
        $app->run();
    }
}