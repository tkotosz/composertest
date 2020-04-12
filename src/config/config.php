<?php

return [
    'app_dir' => '.fooapp',
    'root_requirements' => [
        'tkotosz/fooapp-extension-api' => '*'
    ],
    'repositories' => [
        ['type' => 'path', 'url' => 'core-extensions/*'],
        ['type' => 'path', 'url' => 'fooapp-extension-api/'],
    ]
];