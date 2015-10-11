# store-a-value.feature
  Feature: Store a  scalar value
    In order to reuse a value (or object)
    As a developer
    I want to register a value in the DI container and reuse latter on

  Scenario: Register and read a value from container
    Given I create a container
    And register a "foo" under "key" key
    When I get "key" from container
    Then the value should be "foo"

  Scenario: Read an undefined value throws exception
    Given I create a container
    When I get "unknown.key" from container
    Then I should get an exception