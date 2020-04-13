<?php

namespace Tkotosz\FooApp\ExtensionApi;

interface DependencyInjectionContainer
{
    public function registerServicesFromPath(string $namespace, string $path, array $exclude = []): void;

    public function registerService(string $serviceId, string $className, array $arguments = []): void;

    public function registerServiceAlias(string $alias, string $serviceId): void;
}