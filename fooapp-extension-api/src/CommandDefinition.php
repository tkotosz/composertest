<?php

namespace Tkotosz\FooApp\ExtensionApi;

class CommandDefinition
{
    /** @var string */
    private $name;

    /** @var string */
    private $description;

    public function __construct(string $name, string $description)
    {
        $this->name = $name;
        $this->description = $description;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }
}