# different-domains.feature
  Feature: Load different domains in one run
    In order to separate translation domains
    As a developer
    I want to request translation specifying the domain of it

  Scenario: Request domain in single translation
    Given I have a "php" messages file for "pt_PT" locale
    When I translate "user" on "office" domain
    Then translation should be "colaborador"