<?php

namespace Tkotosz\FooApp\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\TypedReference;
use Tkotosz\FooApp\Console\CompositeCommandsProvider;
use Tkotosz\FooApp\Console\TaggedCommandsProvider;
use Tkotosz\FooApp\DependencyInjection\CommandHandlersContainer;

class RegisterCommandsCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $commandReferences = [];
        $commandHandlerReferences = [];
        $commandProviderReferences = [];

        foreach (array_keys($container->findTaggedServiceIds('fooapp.cli.command')) as $id) {
            if ($container->getDefinition($id)->isAbstract()) {
                continue;
            }

            $commandReferences[] = new Reference($id);
        }

        foreach (array_keys($container->findTaggedServiceIds('fooapp.cli.command.provider')) as $id) {
            if ($container->getDefinition($id)->isAbstract()) {
                continue;
            }

            if ($container->getDefinition($id)->getClass() === CompositeCommandsProvider::class) {
                continue;
            }

            $commandProviderReferences[] = new Reference($id);
        }

        foreach (array_keys($container->findTaggedServiceIds('fooapp.cli.command.handler')) as $id) {
            if ($container->getDefinition($id)->isAbstract()) {
                continue;
            }

            $commandHandlerReferences[$id] = new TypedReference($id, $container->getDefinition($id)->getClass());
        }

        $container
            ->getDefinition(CommandHandlersContainer::class)
            ->addArgument(ServiceLocatorTagPass::register($container, $commandHandlerReferences));

        $container
            ->getDefinition(TaggedCommandsProvider::class)
            ->addArgument($commandReferences);

        $container
            ->getDefinition(CompositeCommandsProvider::class)
            ->addArgument($commandProviderReferences);
    }
}
