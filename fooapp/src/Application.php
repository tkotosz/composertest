<?php

namespace Tkotosz\FooApp;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Yaml\Yaml;
use Tkotosz\FooApp\Console\Command\ExtensionInstallCommand;
use Tkotosz\FooApp\Console\Command\ExtensionListCommand;
use Tkotosz\FooApp\Console\Command\ExtensionRemoveCommand;
use Tkotosz\FooApp\Console\CompositeCommandsProvider;
use Tkotosz\FooApp\Console\SymfonyCommandLoader;
use Tkotosz\FooApp\DependencyInjection\CompilerPass\RegisterCommandsCompilerPass;
use Tkotosz\FooApp\DependencyInjection\DependencyInjectionContainer;
use Tkotosz\FooApp\ExtensionApi\Command;
use Tkotosz\FooApp\ExtensionApi\CommandHandler;
use Tkotosz\FooApp\ExtensionApi\CommandProviderInterface;
use Tkotosz\FooApp\ExtensionApi\Extension;
use Tkotosz\CliAppWrapperApi\Application as ApplicationInterface;
use Tkotosz\CliAppWrapperApi\ApplicationManager;

class Application implements ApplicationInterface
{
    /** @var array */
    private $extensions;

    /** @var ApplicationManager */
    private $applicationManager;

    public function __construct(array $extensions, ApplicationManager $applicationManager)
    {
        $this->extensions = $extensions;
        $this->applicationManager = $applicationManager;
    }

    public function run(): void
    {
        $containerBuilder = new ContainerBuilder();
        $container = new DependencyInjectionContainer($containerBuilder);

        $container->registerServicesFromPath(__NAMESPACE__ . '\\', __DIR__ . '/../src/*');
        $container->registerServiceAlias(CommandProviderInterface::class, CompositeCommandsProvider::class);
        $container->registerForAutoTagging(Command::class, 'fooapp.cli.command');
        $container->registerForAutoTagging(CommandHandler::class, 'fooapp.cli.command.handler');
        $container->registerForAutoTagging(CommandProviderInterface::class, 'fooapp.cli.command.provider');



        $containerBuilder->addCompilerPass(new RegisterCommandsCompilerPass());

        $appDefinition = new Definition(
            \Symfony\Component\Console\Application::class,
            ['Foo App', '1.0.0']
        );
        $appDefinition->setPublic(true);
        $appDefinition->addMethodCall('setCommandLoader', [new Reference(SymfonyCommandLoader::class)]);
        $containerBuilder->setDefinition('fooapp.cli', $appDefinition);




        $treeBuilder = new TreeBuilder('root');
        $builder = $treeBuilder->getRootNode();

        $extensions = (new TreeBuilder('extensions'))->getRootNode();
        $extensions
            ->arrayPrototype()
            ->children()
            ->scalarNode('name')->end()
            ->scalarNode('version')->end()
            ->end()
            ->end();

        $foo = (new TreeBuilder('foo'))->getRootNode();
        $foo
            ->children()
            ->scalarNode('bar')->end()
            ->end();

        $builder
            ->append($extensions)
            ->append($foo);

        $extensions = $this->createExtensions();

        foreach ($extensions as $extension) {
            $extension->initialize();
        }

        foreach ($extensions as $extension) {
            $extension->configure($builder);
        }

        $configTree = $treeBuilder->buildTree();

        $configProcessor = new Processor();

        try {
            $inputConfig = Yaml::parse(file_get_contents(getcwd() . '/fooapp.yml'));
            $processedConfig = $configProcessor->process($configTree, ['root' => $inputConfig]);
        } catch (\Throwable $throwable) {
            echo $throwable->getMessage() . PHP_EOL;
            die;
        }

        foreach ($extensions as $extension) {
            $extension->load($container, $processedConfig);
        }

        $containerBuilder->compile();
        $consoleApp = $containerBuilder->get('fooapp.cli');

        $consoleApp->add(new ExtensionInstallCommand($this->applicationManager));
        $consoleApp->add(new ExtensionListCommand($this->applicationManager));
        $consoleApp->add(new ExtensionRemoveCommand($this->applicationManager));

        $consoleApp->run();
    }

    /**
     * @return Extension[]
     */
    private function createExtensions(): array
    {
        return array_filter(array_map(function ($className) {
            if (!class_exists($className)) {
                throw new \RuntimeException(sprintf('Extension class "%s" not found', $className));
            }

            if (!is_subclass_of($className, Extension::class)) {
                throw new \RuntimeException(sprintf('Extension "%s" must implement "%s" interface', $className, Extension::class));
            }

            return new $className;
        }, $this->extensions));
    }
}