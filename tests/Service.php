<?php
declare(strict_types=1);

namespace Lcobucci\DependencyInjection\Behat\Tests;

final class Service
{
    private string $name;
    private string $mode;

    public function __construct(string $name, string $mode)
    {
        $this->name = $name;
        $this->mode = $mode;
    }

    public function isWorking(string $expectedName, string $expectedMode): bool
    {
        return $expectedName === $this->name && $expectedMode === $this->mode;
    }
}
