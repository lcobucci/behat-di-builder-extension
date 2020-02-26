<?php
declare(strict_types=1);

namespace Lcobucci\DependencyInjection\Behat\Tests;

use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;
use Psr\Container\ContainerInterface;

final class Sample implements Context
{
    private ContainerInterface $container;
    private Service $service;

    /**
     * @AfterSuite
     */
    public static function cleanUpContainer(): void
    {
        TestContainerRemover::remove();
    }

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @Given I have setup the container builder properly
     */
    public function iHaveSetupTheContainerBuilderProperly(): void
    {
        Assert::assertTrue($this->container->has('dynamic'));
        Assert::assertTrue($this->container->has('fixed'));
    }

    /**
     * @When I instantiate the service :service
     */
    public function iInstantiateTheService(string $service): void
    {
        $this->service = $this->container->get($service);
    }

    /**
     * @Then the service should work using :name and :mode as values
     */
    public function theServiceShouldWorkUsingAndAsValues(string $name, string $mode): void
    {
        Assert::assertTrue($this->service->isWorking($name, $mode));
    }
}
