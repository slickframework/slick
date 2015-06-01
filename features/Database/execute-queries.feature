# execute-queries.feature
  Feature: Execute DDL or data change queries
    In order to run queries having the affected rows as result
    As a developer
    I want to execute queries using a database adapter

  Scenario: Create a database table
    Given a database server with:
      | driver | options |
      | sqlite | ["file":":memory:"] |
    When I create adapter using a factory object
    Then I should be able to execute query:
    """
    CREATE TABLE t(
      x INTEGER,
      y,
      z,
      PRIMARY KEY(x ASC)
    );
    """
    And affected rows should be "0"