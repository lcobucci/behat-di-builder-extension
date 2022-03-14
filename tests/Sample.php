<?php
declare(strict_types=1);

namespace Lcobucci\DependencyInjection\Behat\Tests;

use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;
use Psr\Container\ContainerInterface;

use function assert;

final class Sample implements Context
{
    private Service $service;

    public function __construct(private ContainerInterface $container)
    {
    }

    /** @AfterSuite */
    public static function cleanUpContainer(): void
    {
        TestContainerRemover::remove();
    }

    /** @Given I have setup the container builder properly */
    public function iHaveSetupTheContainerBuilderProperly(): void
    {
        Assert::assertTrue($this->container->has('dynamic'));
        Assert::assertTrue($this->container->has('fixed'));
    }

    /**
     * @When I instantiate the service :service
     *
     * @param class-string<Service> $service
     */
    public function iInstantiateTheService(string $service): void
    {
        $instance = $this->container->get($service);
        assert($instance instanceof Service);

        $this->service = $instance;
    }

    /** @Then the service should work using :name and :mode as values */
    public function theServiceShouldWorkUsingAndAsValues(string $name, string $mode): void
    {
        Assert::assertTrue($this->service->isWorking($name, $mode));
    }
}
