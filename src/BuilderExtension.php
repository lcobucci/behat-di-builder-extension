<?php
declare(strict_types=1);

namespace Lcobucci\DependencyInjection\Behat;

use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Psr\Container\ContainerInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class BuilderExtension implements Extension
{
    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter
    public function process(ContainerBuilder $container): void
    {
    }

    public function getConfigKey(): string
    {
        return __NAMESPACE__;
    }

    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter
    public function initialize(ExtensionManager $extensionManager): void
    {
    }

    public function configure(ArrayNodeDefinition $builder): void
    {
        $builder
            ->children()
                ->scalarNode('name')
                    ->defaultValue('test_container')
                ->end()
                ?->scalarNode('container_builder')
                    ->defaultNull()
                ->end()
                ?->arrayNode('packages')
                    ->arrayPrototype()->ignoreExtraKeys(false)->end()
                ?->end()
            ->end()
        ->end();
    }

    /** @inheritDoc */
    public function load(ContainerBuilder $container, array $config): void
    {
        $container->setDefinition(
            $config['name'],
            $this->createContainerDefinition($config['packages'], $config['container_builder']),
        );
    }

    /** @param array<string, mixed> $packages */
    private function createContainerDefinition(array $packages, ?string $builderFile = null): Definition
    {
        $definition = new Definition(ContainerInterface::class, [$packages, $builderFile]);

        $definition->setFactory([ContainerFactory::class, 'createContainer']);
        $definition->addTag('helper_container.container');
        $definition->setShared(false);
        $definition->setPublic(true);

        return $definition;
    }
}
