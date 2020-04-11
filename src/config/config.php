<?php

return [
    'app_dir' => '.fooapp',
    'root_requirements' => [
        'composer/composer' => '2.0.x-dev',
        'tkotosz/composer-wrapper' => '*',
        'tkotosz/fooapp' => '*',
        'tkotosz/fooapp-console-extension' => '*',
        'tkotosz/fooapp-extension-installer-extension' => '*'
    ],
    'composer_repositories' => [
        ['type' => 'path', 'url' => 'core-extensions/*'],
        ['type' => 'path', 'url' => 'fooapp/'],
        ['type' => 'path', 'url' => 'composer-wrapper/'],
    ]
];