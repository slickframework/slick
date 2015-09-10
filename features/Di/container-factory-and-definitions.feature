# container-factory-and-definitions.feature
  Feature: Create container using a container builder and a file with definitions
    In order to create containers in my application
    As a developer
    I want to write a definitions file that will be used by the container builder

  Rules:
    - Definitions file is a PHP file that returns an array with key/value pairs
      where the keys are the definition IDs and values the correspondent
      definition.
    - Simple scalar values are used as is
    - Callbacks (callable) can be set
    - Alias and references to other entries are marked with '@' prefix
    - Object definitions should be created with ObjectDefinition helper class.