<?php
declare(strict_types=1);

namespace Lcobucci\DependencyInjection\Behat\Tests;

use Generator;
use Lcobucci\DependencyInjection\CompilerPassListProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class Package implements CompilerPassListProvider
{
    public function __construct(private string $name)
    {
    }

    public function getCompilerPasses(): Generator
    {
        yield [$this->additionalServices(), PassConfig::TYPE_BEFORE_OPTIMIZATION];
    }

    private function additionalServices(): CompilerPassInterface
    {
        return new class ($this->name) implements CompilerPassInterface
        {
            public function __construct(private string $name)
            {
            }

            public function process(ContainerBuilder $container): void
            {
                $container->setDefinition('dynamic', new Definition(Service::class, [$this->name, '%mode%']));
                $container->setDefinition('fixed', new Definition(Service::class, ['me', 'prod']));
            }
        };
    }
}
