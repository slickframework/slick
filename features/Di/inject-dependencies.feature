# inject-dependencies.feature
  Feature: Inject known dependencies on entry creation
    In order to easy the dependency injection on objects from container
    As a developer
    I want that all objects I get from container have the known dependencies inject.

  Rules:
    - All matching entry IDs should be injected
    - Methods that start with set* and have one argument, if the argument type hint
      matched and entry ID it will be called
    - Properties or methods with @ignoreInject will be ignored by the container
    - Properties or methods with @inject will be consider by the container and an
      exception will be thrown if the container cannot satisfy all dependencies
    - @inject without arguments should be used in conjunction with @var
    - @inject <value> will be used as container entry ID

# Please check the Di\Fixtures\InjectableClass to see the rules applied.

  Scenario: Create/make an object
    Given I create a container
    And register a "test" under "name" key
    When I use container to make "Di\Fixtures\InjectableClass"
    Then the value should be a "Di\Fixtures\InjectableClass" object