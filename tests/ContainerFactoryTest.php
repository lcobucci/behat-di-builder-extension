<?php
declare(strict_types=1);

namespace Lcobucci\DependencyInjection\Behat\Tests;

use Lcobucci\DependencyInjection\Behat\ContainerCannotBeBuilt;
use Lcobucci\DependencyInjection\Behat\ContainerFactory;
use PHPUnit\Framework\Attributes as PHPUnit;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;

use function dirname;

#[PHPUnit\CoversClass(ContainerFactory::class)]
#[PHPUnit\CoversClass(ContainerCannotBeBuilt::class)]
final class ContainerFactoryTest extends TestCase
{
    #[PHPUnit\Before]
    #[PHPUnit\After]
    public function removeBuiltContainer(): void
    {
        TestContainerRemover::remove();
    }

    #[PHPUnit\Test]
    public function createContainerShouldBuildATestContainerWithOneServiceWhenNoPackageAndNoConfigFileIsGiven(): void
    {
        $container = ContainerFactory::createContainer([]);

        self::assertInstanceOf(Container::class, $container);
        self::assertSame(['service_container'], $container->getServiceIds());
    }

    #[PHPUnit\Test]
    public function createContainerShouldBeReturnAContainerWithAllRegisteredPackages(): void
    {
        $container = ContainerFactory::createContainer(
            [Package::class => ['we']],
            dirname(__DIR__) . '/config/container_builder.php',
        );

        self::assertInstanceOf(Service::class, $container->get('dynamic'));
        self::assertInstanceOf(Service::class, $container->get('fixed'));
    }

    #[PHPUnit\Test]
    public function createContainerShouldThrownAnExceptionWhenPackageListHasInvalidClasses(): void
    {
        $this->expectException(ContainerCannotBeBuilt::class);
        $this->expectExceptionMessage('The package class "' . Test::class . '" could not be loaded, is it correct?');

        // @phpstan-ignore-next-line this class doesn't exist and can safely be ignored
        ContainerFactory::createContainer([Test::class => []]);
    }

    #[PHPUnit\Test]
    public function createContainerShouldThrownAnExceptionBuilderFileIsNotReadable(): void
    {
        $file = __DIR__ . '/test.php';

        $this->expectException(ContainerCannotBeBuilt::class);
        $this->expectExceptionMessage('The file "' . $file . '" is not readable, is this the correct path?');

        ContainerFactory::createContainer([], $file);
    }
}
