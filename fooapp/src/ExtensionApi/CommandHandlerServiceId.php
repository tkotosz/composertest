<?php

namespace Tkotosz\FooApp\ExtensionApi;

class CommandHandlerServiceId
{
    /** @var string */
    private $serviceId;

    public function __construct(string $serviceId)
    {
        $this->serviceId = $serviceId;
    }

    public function serviceId(): string
    {
        return $this->serviceId;
    }
}