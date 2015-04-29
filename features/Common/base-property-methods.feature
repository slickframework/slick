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

  Scenario: Write to a protected property as if it has public access
    Given I coded a class extending Slick\Common\Base
    And class has property "name" with "@readwrite" annotation
    When I set property "name" equals to "Other test"
    And I retrieve "name" property
    Then I should get "Other test" value

  Scenario: Read protected property through get[<Property>]() magic method
    Given I coded a class extending Slick\Common\Base
    And class has property "name" with "@readwrite" annotation
    When I call "getName" method
    Then I should get "Test" value

  Scenario: Set protected property through set[<Property>]() magic method
    Given I coded a class extending Slick\Common\Base
    And class has property "name" with "@readwrite" annotation
    When I call "setName" method with "myTest"
    And I retrieve "name" property
    Then I should get "myTest" value

  Scenario: Check protected property boolean value through is[<Property>]() magic method
    Given I coded a class extending Slick\Common\Base
    And class has property "name" with "@readwrite" annotation
    When I call "isAdult" method
    Then I should get true boolean value