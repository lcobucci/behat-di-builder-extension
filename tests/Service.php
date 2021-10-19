<?php
declare(strict_types=1);

namespace Lcobucci\DependencyInjection\Behat\Tests;

final class Service
{
    public function __construct(private string $name, private string $mode)
    {
    }

    public function isWorking(string $expectedName, string $expectedMode): bool
    {
        return $expectedName === $this->name && $expectedMode === $this->mode;
    }
}
