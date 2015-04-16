# inspector.feature
  Feature: class inspection
    In order to add special behavior to a class
    As a developer
    I want to inspect a class for properties, methods and annotations

    Annotations are comment tags that start with @ prefix
    Annotation examples:
    - simple: @tag
    - with value: @tag value
    - with value and parameters: @tag value, key=value
    - with parameters only: @tag key1=value1, key2=value2

    Tag parameter parsing rules:
    - all values are strings by default, if no other rule applies
    - values like [..., ...] are converted to array (list)
    - values like {...} are parsed as JSON
    - values like "null" will be set as NULL
    - values like "3" will be set as integer
    - values like "2.03" will be set as float

    Background: an inspector object with a class with comments
      Given a class with comment blocks
      And have an inspector with it

    Scenario: Get class annotations
      When I inspect class annotations
      Then I should get an annotations list of the class
      And class annotations should contain an annotation named "behatContext"

    Scenario: Get class property names as a list
      When I inspect class properties
      Then I get an array of properties containing "inspector"

    Scenario: Get property annotations
      When I inspect property "inspector" annotations
      Then I should get an annotations list of the property
      And property annotations should contain an annotation named "var"

    Scenario: Get class method names as a list
      When I inspect class methods
      Then I get an array of methods containing "createInspector"

    Scenario: Get property annotations
      When I inspect method "createInspector" annotations
      Then I should get an annotations list of the method
      And method annotations should contain an annotation named "Given"