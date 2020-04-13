<?php

return [
    'patchers' => [
        function ($filePath, $prefix, $contents) {
            return str_replace(
                '\\'.$prefix.'\Composer\Autoload\ClassLoader',
                '\Composer\Autoload\ClassLoader',
                $contents
            );
        },
    ],
    'files-whitelist' => ['config.php'],
    'whitelist' => [
        'Tkotosz\CliAppWrapperApi\*',
        'Composer\Autoload\ClassLoader'
    ]
];