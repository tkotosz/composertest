<?php

return [
    'app_dir' => '.fooapp',
    'app_package' => 'tkotosz/fooapp',
    'app_version' => '*',
    'app_factory' => 'Tkotosz\FooApp\ApplicationFactory',
    'repositories' => [
        ['type' => 'path', 'url' => 'fooapp/'],
        ['type' => 'path', 'url' => 'core-extensions/*'],
    ]
];