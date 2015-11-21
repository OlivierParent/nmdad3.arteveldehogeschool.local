# language: en
Feature: Homepage
  In order to access secured content of the website
  As a visitor
  I need to see links to the login forms

  @mink:default
  Scenario: Links on homepage
    Given I am on the homepage
      And print current URL
    Then I should see text matching "NMDAD-III Demo"
      And I should see text matching "ArteBlog"
      And I should see "Home"
      And I should see "Login"
      And I should see "Register"

# To see or find the predefined steps in the context (MinkContext), use:
# vagrant@homestead$ bin/behat -di
# vagrant@homestead$ bin/behat -dl

# To run only this test
# vagrant@homestead$ bin/behat @AppBundle/homepage.feature