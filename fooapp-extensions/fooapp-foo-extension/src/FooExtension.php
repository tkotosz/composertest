<?php

namespace Tkotosz\FooApp\FooExtension;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Tkotosz\FooApp\ExtensionApi\DependencyInjectionContainer;
use Tkotosz\FooApp\ExtensionApi\Extension;

class FooExtension implements Extension
{
    public function initialize(): void
    {
        // TODO: Implement initialize() method.
    }

    public function configure(ArrayNodeDefinition $builder): void
    {
        // add a baz field under the existing foo
        $builder->find('foo')
            ->children()
                ->scalarNode('baz')->end()
            ->end();

        // add a new bar config section
        $builder
            ->children()
                ->arrayNode('bar')
                    ->children()
                        ->scalarNode('bar')->end()
                    ->end()
                ->end()
            ->end();

        // alternative way of adding a new section
        $foo = (new TreeBuilder('foo_extension'))->getRootNode();
        $foo
            ->children()
                ->scalarNode('foo_command_alias')->end()
            ->end();

        $builder->append($foo);
    }

    public function load(DependencyInjectionContainer $container, array $config): void
    {
        $container->registerServicesFromPath(__NAMESPACE__ . '\\', __DIR__ . '/../src/*');
        $container->registerService(Config::class, Config::class, [$config['foo_extension'] ?? []]);
    }
}