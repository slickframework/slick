# collection.feature
  Feature: Base collection container class
    In order to unify the use of object collections
    As a developer
    I need to have a base collection with is a specialized object container

  Rules:
    - Collection extends from Countable, IteratorAggregate, ArrayAccess, Serializable
    - need to have a method 'asArray' that will return collection data as an pure PHP array
    - need to have a 'each' that accepts a callable and iterates the elements
        - If the callable returns false it will break the iteration
    - can be cleared
    - have a method (isEmpty) to check collection emptiness

  Background: Having a collection
    Given I create a collection with elements:
      |value|
      |foo  |
      |bar  |

  Scenario: Using collection as countable
    When I use count on the collection
    Then I should have 2 elements

  Scenario: clear collection and check emptiness
    When I clear the collection
    Then I should have 0 elements
    And collection isClear is true
