# custom-annotation.feature
  Feature: Create custom annotations
    I order to add more features to an annotation
    As a developer
    I want to create custom annotation classes

  Rules:
    - Annotations must implement Slick\Common\AnnotationInterface
    - Tags can use namespaces, FQN class name and use statements for reference

  Scenario: Retrieve custom annotation form class (FQN)
    Given class "Common\Fixtures\CustomAnnotation" implements "AnnotationInterface"
    And class "Common\Fixtures\AnnotationTest" has dock block with "@Common\Fixtures\CustomAnnotation"
    When I inspect "Common\Fixtures\AnnotationTest" class annotations
    Then I should have a annotations list with "@Common\Fixtures\CustomAnnotation" object

  Scenario: Retrieve custom annotation from class alias
    Given class "Common\Fixtures\CustomAnnotation" implements "AnnotationInterface"
    And class "Common\Fixtures\AnnotationAliasTest" has dock block with "@CustomAnnotation"
    When I inspect "Common\Fixtures\AnnotationAliasTest" class annotations
    Then I should have a annotations list with "@Common\Fixtures\CustomAnnotation" object

  Scenario: Retrieve custom annotation from class name in same namespace
    Given class "Common\Fixtures\CustomAnnotation" implements "AnnotationInterface"
    And class "Common\Fixtures\AnnotationNsTest" has dock block with "@CustomAnnotation"
    When I inspect "Common\Fixtures\AnnotationNsTest" class annotations
    Then I should have a annotations list with "@Common\Fixtures\CustomAnnotation" object