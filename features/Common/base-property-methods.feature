# base-property-methods.feature
  Feature: a base class/trait to help assign and read properties
    In order to keep class construction simple
    As a developer
    I need a base class/trait that handles object creation, property assign and read

  Scenario: Read a protect property as if it has public access
    Given I coded a class extending Slick\Common\Base
    And class has property "name" with "@readwrite" annotation
    When I retrieve "name" property
    Then I should get "Test" value