# language: en
Feature: Homepage
  In order to access secured content of the website
  As a visitor
  I need to see links to the login forms

  @mink:default
  Scenario: Links on homepage
    Given I am on the homepage
    Then I should see text matching "NMDAD-III Demo"
      And I should see text matching "ArteBlog"

# To see or find the predefined steps in the context (MinkContext), use:
# vagrant@homestead$ bin/behat -di
# vagrant@homestead$ bin/behat -dl
