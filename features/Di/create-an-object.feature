# create-an-object.feature
  Feature: Create an object providing its class name and construct arguments
    In order to create an object with its dependencies injected
    As a developer
    I want to call a container "make" method with the class name and arguments
    and create the class instance.

  Scenario: create a simple object
    Given I create a container
    When I make class "stdClass"
    Then I should get an object