<?php
declare(strict_types=1);

namespace Lcobucci\DependencyInjection\Behat\Tests;

use Lcobucci\DependencyInjection\Behat\ContainerBuildingException;
use Lcobucci\DependencyInjection\Behat\ContainerFactory;
use Symfony\Component\DependencyInjection\Container;

final class ContainerFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @before
     * @after
     */
    public function removeBuiltContainer(): void
    {
        TestContainerRemover::remove();
    }

    /**
     * @test
     *
     * @covers \Lcobucci\DependencyInjection\Behat\ContainerFactory
     */
    public function createContainerShouldBuildATestContainerWithOneServiceWhenNoPackageAndNoConfigFileIsGiven(): void
    {
        /** @var Container $container */
        $container = ContainerFactory::createContainer([]);

        self::assertInstanceOf(Container::class, $container);
        self::assertSame(['service_container'], $container->getServiceIds());
    }

    /**
     * @test
     *
     * @covers \Lcobucci\DependencyInjection\Behat\ContainerFactory
     */
    public function createContainerShouldBeReturnAContainerWithAllRegisteredPackages(): void
    {
        $container = ContainerFactory::createContainer(
            [Package::class => ['we']],
            dirname(__DIR__) . '/config/container_builder.php'
        );

        self::assertInstanceOf(Service::class, $container->get('dynamic'));
        self::assertInstanceOf(Service::class, $container->get('fixed'));
    }

    /**
     * @test
     *
     * @covers \Lcobucci\DependencyInjection\Behat\ContainerFactory
     * @covers \Lcobucci\DependencyInjection\Behat\ContainerBuildingException
     */
    public function createContainerShouldThownAnExceptionWhenPackageListHasInvalidClasses(): void
    {
        $this->expectException(ContainerBuildingException::class);
        $this->expectExceptionMessage('The package class "' . Test::class . '" could not be loaded, is it correct?');

        ContainerFactory::createContainer([Test::class => []]);
    }

    /**
     * @test
     *
     * @covers \Lcobucci\DependencyInjection\Behat\ContainerFactory
     * @covers \Lcobucci\DependencyInjection\Behat\ContainerBuildingException
     */
    public function createContainerShouldThownAnExceptionBuilderFileIsNotReadable(): void
    {
        $file = __DIR__ . '/test.php';

        $this->expectException(ContainerBuildingException::class);
        $this->expectExceptionMessage('The file "' . $file . '" is not readable, is this the correct path?');

        ContainerFactory::createContainer([], $file);
    }
}
