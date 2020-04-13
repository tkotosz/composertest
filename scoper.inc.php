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
    'files-whitelist' => ['src/config/config.php'],
    'whitelist' => [
        'Tkotosz\FooApp\ExtensionApi\*',
        'Composer\Autoload\ClassLoader',
        'Symfony\Component\*', // part of the API for now
    ]
];