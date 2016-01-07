# different-messages-types.feature
  Feature: Use Gettext files
    In order to facilitate the translation done by others
    As a developer
    I want to use Gettext (.mo) message files files

  Scenario: Use Gettext files
    Given I have a "gettext" messages file for "pt_PT" locale
    When I translate "test" on "other" domain
    Then translation should be "teste"