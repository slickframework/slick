# querying-data.feature
  Feature: Retrieve data using queries
    In order to query data from a database server
    As a developer
    I want to run select queries using a database adapter

  Scenario: Select rows from a table
    Given a database server with:
      | driver | options |
      | sqlite | ["file":":memory:"] |
    And I create adapter using a factory object
    And I execute query:
    """
    CREATE TABLE t(
      x INTEGER,
      y,
      z,
      PRIMARY KEY(x ASC)
    );
    """
    And I execute query:
    """
    INSERT INTO t VALUES(1, "me", "you");
    INSERT INTO t VALUES(2, "you", "other");
    """
    When I run query:
    """
    SELECT * FROM t;
    """
    Then I should get 2 records