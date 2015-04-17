# custom-annotation.feature
  Feature: Create custom annotations
    I order to add more features to an annotation
    As a developer
    I want to create custom annotation classes

    @current
  Scenario: Retrieve custom annotation form class
    Given class "Common\Fixtures\CustomAnnotation" implements "AnnotationInterface"
    And class "AnnotationTest" has dock block with "@Common\CustomAnnotation"
    When I inspect "AnnotationTest" class annotations
    Then I should have a annotations list with "CustomAnnotation" object