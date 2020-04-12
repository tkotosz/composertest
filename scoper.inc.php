<?php

return [
    'files-whitelist' => ['src/config/config.php'],
    'whitelist' => [
        'Tkotosz\FooApp\ExtensionApi\*',
        'Composer\*' //cannot be scoped correctly - generating classes from string when generating autoloader
    ]
];