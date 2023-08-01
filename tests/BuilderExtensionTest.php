<?php
declare(strict_types=1);

namespace Lcobucci\DependencyInjection\Behat\Tests;

use Behat\Testwork\ServiceContainer\ExtensionManager;
use Lcobucci\DependencyInjection\Behat\BuilderExtension;
use Lcobucci\DependencyInjection\Behat\ContainerFactory;
use PHPUnit\Framework\Attributes as PHPUnit;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;

#[PHPUnit\CoversClass(BuilderExtension::class)]
final class BuilderExtensionTest extends TestCase
{
    #[PHPUnit\Test]
    public function processShouldNotDoAnything(): void
    {
        $builder = new ContainerBuilder();

        $extension = new BuilderExtension();
        $extension->process($builder);

        self::assertEquals(new ContainerBuilder(), $builder);
    }

    #[PHPUnit\Test]
    public function initializeShouldNotDoAnything(): void
    {
        $extensionManager = new ExtensionManager([]);

        $extension = new BuilderExtension();
        $extension->initialize($extensionManager);

        self::assertEquals(new ExtensionManager([]), $extensionManager);
    }

    #[PHPUnit\Test]
    public function getConfigKeyShouldSimplyReturnTheClassNamespace(): void
    {
        $extension = new BuilderExtension();

        self::assertSame('Lcobucci\DependencyInjection\Behat', $extension->getConfigKey());
    }

    #[PHPUnit\Test]
    public function configureGenerateAValidConfiguration(): void
    {
        $extension   = new BuilderExtension();
        $treeBuilder = new TreeBuilder('root');
        $processor   = new Processor();

        $extension->configure($treeBuilder->getRootNode());

        $config = $processor->process($treeBuilder->buildTree(), []);

        self::assertSame(['name' => 'test_container', 'container_builder' => null, 'packages' => []], $config);
    }

    #[PHPUnit\Test]
    public function loadShouldAppendADefinitionForTheDIContainer(): void
    {
        $sfBuilder = new ContainerBuilder();
        $config    = [
            'name' => 'container',
            'container_builder' => 'a-file.php',
            'packages' => [Package::class => 'test'],
        ];

        $extension = new BuilderExtension();
        $extension->load($sfBuilder, $config);

        $definition = $sfBuilder->getDefinition('container');

        self::assertSame(ContainerInterface::class, $definition->getClass());
        self::assertSame([ContainerFactory::class, 'createContainer'], $definition->getFactory());
        self::assertSame([Package::class => 'test'], $definition->getArgument(0));
        self::assertSame('a-file.php', $definition->getArgument(1));
        self::assertTrue($definition->isPublic());
        self::assertFalse($definition->isPrivate());
        self::assertFalse($definition->isShared());
    }
}
