# store-a-callable.feature
  Feature: Store a callable in container
    In order to boost performance and instantiate entries only when they are needed
    As a developer
    I want to define an entry that will execute a callable when requested

  Scenario: Define a callable that will be executed
    Given I create a container
    And I define a callable that returns an object
    And register it under "callable-test" key
    When I get "callable-test" from container
    Then the value should be an object