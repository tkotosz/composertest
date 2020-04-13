<?php

namespace Tkotosz\FooApp\ExtensionApi;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

interface Extension
{
    public function initialize(): void;
    public function configure(ArrayNodeDefinition $builder): void;
    public function load(DependencyInjectionContainer $container, array $config): void;
}