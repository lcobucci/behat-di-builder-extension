Feature: Test container can be built

  Background:
    Given I have setup the container builder properly

  Scenario: Service with dynamic arguments
     When I instantiate the service "dynamic"
     Then the service should work using "you" and "test" as values

  Scenario: Service with fixed arguments
     When I instantiate the service "fixed"
     Then the service should work using "me" and "prod" as values
