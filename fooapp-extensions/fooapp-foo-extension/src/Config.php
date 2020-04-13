<?php

namespace Tkotosz\FooApp\FooExtension;

class Config
{
    /** @var array */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getFooCommandAlias()
    {
        return $this->config['foo_command_alias'] ?? 'hmm:run';
    }
}