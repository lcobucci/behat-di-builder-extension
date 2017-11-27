<?php
declare(strict_types=1);

namespace Lcobucci\DependencyInjection\Behat\Tests;

use Behat\Testwork\ServiceContainer\ExtensionManager;
use Lcobucci\DependencyInjection\Behat\BuilderExtension;
use Lcobucci\DependencyInjection\Behat\ContainerFactory;
use Psr\Container\ContainerInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class BuilderExtensionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     *
     * @covers \Lcobucci\DependencyInjection\Behat\BuilderExtension::process
     */
    public function processShouldNotDoAnything(): void
    {
        $builder = new ContainerBuilder();

        $extension = new BuilderExtension();
        $extension->process($builder);

        self::assertEquals(new ContainerBuilder(), $builder);
    }

    /**
     * @test
     *
     * @covers \Lcobucci\DependencyInjection\Behat\BuilderExtension::initialize
     */
    public function initializeShouldNotDoAnything(): void
    {
        $extensionManager = new ExtensionManager([]);

        $extension = new BuilderExtension();
        $extension->initialize($extensionManager);

        self::assertEquals(new ExtensionManager([]), $extensionManager);
    }

    /**
     * @test
     *
     * @covers \Lcobucci\DependencyInjection\Behat\BuilderExtension::getConfigKey
     */
    public function getConfigKeyShouldSimplyReturnTheClassNamespace(): void
    {
        $extension = new BuilderExtension();

        self::assertSame('Lcobucci\DependencyInjection\Behat', $extension->getConfigKey());
    }

    /**
     * @test
     *
     * @covers \Lcobucci\DependencyInjection\Behat\BuilderExtension::configure
     */
    public function configureGenerateAValidConfiguration(): void
    {
        $extension   = new BuilderExtension();
        $treeBuilder = new TreeBuilder();
        $processor   = new Processor();

        $extension->configure($treeBuilder->root('root'));

        $config = $processor->process($treeBuilder->buildTree(), []);

        self::assertSame(['name' => 'test_container', 'container_builder' => null, 'packages' => []], $config);
    }

    /**
     * @test
     *
     * @covers \Lcobucci\DependencyInjection\Behat\BuilderExtension::load
     * @covers \Lcobucci\DependencyInjection\Behat\BuilderExtension::createContainerDefinition
     */
    public function loadShouldAppendADefinitionForTheDIContainer(): void
    {
        $sfBuilder = new ContainerBuilder();
        $config    = [
            'name' => 'container',
            'container_builder' => 'a-file.php',
            'packages' => [Package::class => 'test']
        ];

        $extension = new BuilderExtension();
        $extension->load($sfBuilder, $config);

        $definition = $sfBuilder->getDefinition('container');

        self::assertSame(ContainerInterface::class, $definition->getClass());
        self::assertSame([ContainerFactory::class, 'createContainer'], $definition->getFactory());
        self::assertSame([Package::class => 'test'], $definition->getArgument(0));
        self::assertSame('a-file.php', $definition->getArgument(1));
        self::assertFalse($definition->isShared());
    }
}
