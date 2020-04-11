<?php

namespace Tkotosz\FooAppCli;

use Tkotosz\ComposerWrapper\Composer;
use Tkotosz\ComposerWrapper\ComposerConfig;

class ApplicationInitializer
{
    public static function init($appConfig): void
    {
        echo "Initializing application..." . PHP_EOL;

        $composerConfig = new ComposerConfig(
            $appConfig['app_dir'],
            $appConfig['root_requirements'],
            $appConfig['composer_repositories']
        );

        $composer = new Composer($composerConfig);

        $result = $composer->init();

        if ($result->isError()) {
            echo $result->output();
            exit(255);
        }
    }
}