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
    'files-whitelist' => ['src/config.php'],
    'whitelist' => [
        'Tkotosz\WrappedCliApp\*',
        'Composer\Autoload\ClassLoader'
    ]
];