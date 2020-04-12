<?php

return [
    'app_dir' => '.fooapp',
    'root_requirements' => [
        'tkotosz/fooapp' => '*',
        'tkotosz/fooapp-console-extension' => '*'
    ],
    'repositories' => [
        ['type' => 'path', 'url' => 'core-extensions/*'],
        ['type' => 'path', 'url' => 'fooapp/'],
    ],
    'main_application' => 'Tkotosz\FooApp\Application'
];