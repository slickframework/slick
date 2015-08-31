# store-an-alias.feature
  Feature: Store an alias definition
    In order to reuse an existing definition
    As a developer
    I wan to define an alias for an existing definition

  Scenario: use an alias definition
    Given I create a container
    And I make class "stdClass"
    And register an alias for "stdClass" as "object"
    When I get "object" from container
    Then they should be the same object