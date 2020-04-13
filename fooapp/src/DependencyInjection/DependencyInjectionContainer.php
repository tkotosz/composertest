<?php

namespace Tkotosz\FooApp\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\GlobFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Tkotosz\FooApp\ExtensionApi\DependencyInjectionContainer as DependencyInjectionContainerInterface;
use Tkotosz\FooApp\ExtensionApi\ServiceReference;

class DependencyInjectionContainer implements DependencyInjectionContainerInterface
{
    /** @var ContainerBuilder */
    public $containerBuilder;

    public function __construct(ContainerBuilder $containerBuilder)
    {
        $this->containerBuilder = $containerBuilder;
    }

    public function registerServicesFromPath(string $namespace, string $path, array $exclude = []): void
    {
        $loader = new GlobFileLoader($this->containerBuilder, new FileLocator($path));

        $definition = new Definition();
        $definition
            ->setAutowired(true)
            ->setAutoconfigured(true)
            ->setPublic(false);

        $loader->registerClasses($definition, $namespace, $path, $exclude);
    }

    public function registerService(string $serviceId, string $className, array $arguments = []): void
    {
        $symfonyDiDefinitionArguments = [];
        foreach ($arguments as $key => $argument) {
            if ($argument instanceof ServiceReference) {
                $symfonyDiDefinitionArguments[$key] = new Reference($argument->toString());
            } else {
                $symfonyDiDefinitionArguments[$key] = $argument;
            }
        }

        $this->containerBuilder->setDefinition($serviceId, new Definition($className, $symfonyDiDefinitionArguments));
    }

    public function registerServiceAlias(string $alias, string $serviceId): void
    {
        $this->containerBuilder->setAlias($alias, $serviceId);
    }

    public function registerForAutoTagging(string $interface, string $tag): void
    {
        $this->containerBuilder->registerForAutoconfiguration($interface)->addTag($tag);
    }
}