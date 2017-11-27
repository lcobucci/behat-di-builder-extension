<?php
declare(strict_types=1);

namespace Lcobucci\DependencyInjection\Behat;

use Lcobucci\DependencyInjection\Builder;
use Lcobucci\DependencyInjection\ContainerBuilder;
use Psr\Container\ContainerInterface;

final class ContainerFactory
{
    public static function createContainer(array $packages, ?string $builderFile = null): ContainerInterface
    {
        $builder = self::createBuilder($builderFile);

        foreach ($packages as $package => $arguments) {
            if (! class_exists($package)) {
                throw ContainerBuildingException::nonExistingPackage($package);
            }

            $builder->addPackage($package, $arguments);
        }

        return $builder->getTestContainer();
    }

    private static function createBuilder(?string $builderFile): Builder
    {
        if ($builderFile === null) {
            return new ContainerBuilder();
        }

        if (! is_readable($builderFile)) {
            throw ContainerBuildingException::nonReadableFile($builderFile);
        }

        return require $builderFile;
    }
}
