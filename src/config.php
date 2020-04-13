<?php

return [
    'app_package' => 'tkotosz/fooapp',
    'app_version' => '*',
    'app_dir' => '.fooapp',
    'app_config_file' => 'fooapp.yml',
    'app_factory' => 'Tkotosz\FooApp\ApplicationFactory',
    'repositories' => [
        ['type' => 'path', 'url' => 'fooapp/'],
        ['type' => 'path', 'url' => 'core-extensions/*'],
    ]
];