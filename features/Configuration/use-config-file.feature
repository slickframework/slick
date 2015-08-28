# use-config-file.feature
  Feature: Load a config file and use it
    In order to read configuration values from a PHP file with an array in it
    As a developer
    I want to use a factory class that loads up a configuration driver given the file name

  options:
    - default driver is PHP arrays
    - file extensions are set based on configuration type
    - factory will use a list of possible places (paths) where configuration file are in

  Scenario: Load config file
    Given I has the config file "config.php" in "./"
    And file contains value "test" under "test" key
    When I used configuration factory to get "config"
    Then I should be able to read "test" value
    And it should be equal to "test"