<?php
declare(strict_types=1);

namespace Lcobucci\DependencyInjection\Behat;

use Lcobucci\DependencyInjection\Builder;
use Lcobucci\DependencyInjection\Config\Package;
use Lcobucci\DependencyInjection\ContainerBuilder;
use Psr\Container\ContainerInterface;

use function class_exists;
use function is_readable;

final class ContainerFactory
{
    /** @param array<class-string<Package>, list<mixed>> $packages */
    public static function createContainer(array $packages, ?string $builderFile = null): ContainerInterface
    {
        $builder = self::createBuilder($builderFile);

        foreach ($packages as $package => $arguments) {
            if (! class_exists($package)) {
                throw ContainerCannotBeBuilt::nonExistingPackage($package);
            }

            $builder->addPackage($package, $arguments);
        }

        return $builder->getTestContainer();
    }

    private static function createBuilder(?string $builderFile): Builder
    {
        if ($builderFile === null) {
            return ContainerBuilder::xml(__FILE__, 'MyApp');
        }

        if (! is_readable($builderFile)) {
            throw ContainerCannotBeBuilt::nonReadableFile($builderFile);
        }

        return require $builderFile;
    }
}
