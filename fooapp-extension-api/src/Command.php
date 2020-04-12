<?php

namespace Tkotosz\FooApp\ExtensionApi;

class Command
{
    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name;
    }
}