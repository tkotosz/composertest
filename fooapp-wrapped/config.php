<?php

return [
    'app_name' => 'Foo App',
    'app_package' => 'tkotosz/fooapp',
    'app_version' => '*',
    'app_dir' => '.fooapp',
    'app_factory' => 'Tkotosz\FooApp\ApplicationFactory',
    'user_config_file' => 'fooapp.yml',
    'repositories' => [
        ['type' => 'path', 'url' => '../fooapp/'],
        ['type' => 'path', 'url' => '../fooapp-extensions/*'],
    ]
];