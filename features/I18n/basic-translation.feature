# basic-translation
  Feature: Basic string translation
    In order to add internationalization to my apps
    As a developer
    I need to translate strings

  Scenario: Translate single string
    Given I have a "php" messages file for "pt_PT" locale
    When I translate "Hello world"
    Then translation should be "Olá mundo"

  Scenario: Translate plural
    Given I have a "php" messages file for "pt_PT" locale
    When I translate "User" plural "Users" with 3 as count
    Then translation should be "Utilizadores"