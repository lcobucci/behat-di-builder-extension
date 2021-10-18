<?php
declare(strict_types=1);

namespace Lcobucci\DependencyInjection\Behat;

use RuntimeException;

use function sprintf;

final class ContainerCannotBeBuilt extends RuntimeException
{
    public static function nonReadableFile(string $filename): self
    {
        return new self(
            sprintf('The file "%s" is not readable, is this the correct path?', $filename),
        );
    }

    public static function nonExistingPackage(string $package): self
    {
        return new self(
            sprintf('The package class "%s" could not be loaded, is it correct?', $package),
        );
    }
}
