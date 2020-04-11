<?php

$appConfig = require __DIR__ . '/config.php';
$appAutoload = __DIR__ . '/../../' . $appConfig['app_dir'] . '/autoload.php';

if (!file_exists($appAutoload)) {
    require __DIR__ . '/../../vendor/autoload.php';
    Tkotosz\FooAppCli\ApplicationInitializer::init($appConfig);
    foreach(spl_autoload_functions() as $function) {
        spl_autoload_unregister($function);
    }
}

$appConfig['extensions'] = require $appConfig['app_dir'] . '/extensions.php';

require $appAutoload;
