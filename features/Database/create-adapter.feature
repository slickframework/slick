# create-adapter.feature
  Feature: Create a database adapter
    In order to connect to a database server
    As a developer
    I want to create a database adapter using my credentials

  Scenario: Create an adapter using package factory
    Given a database server with:
    | driver | options |
    | sqlite | ["file":":memory:"] |
    When I create adapter using a factory object
    Then I should be able to run query "PRAGMA encoding;"