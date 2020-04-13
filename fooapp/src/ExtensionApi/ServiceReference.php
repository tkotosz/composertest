<?php

namespace Tkotosz\FooApp\ExtensionApi;

class ServiceReference
{
    /** @var string */
    private $serviceId;

    public function __construct(string $serviceId)
    {
        $this->serviceId = $serviceId;
    }

    public function toString(): string
    {
        return $this->serviceId;
    }
}