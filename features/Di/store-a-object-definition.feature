# store-a-object-definition.feature
  Feature: Store a definition of object creation
    In order to create an object by defining its steps
    As a developer
    I want to register an object definition providing class name, constructor parameters,
    method calls and property assignments to create the object.

  Scenario: Define an object
    Given I create object definition "Di\Fixtures\Object"
    And I set constructor parameters with "test"
    And I set property "name" to "Dummy object"
    And I create a container
    And register it under "object-test" key
    When I get "object-test" from container
    Then the value should be a "Di\Fixtures\Object" object