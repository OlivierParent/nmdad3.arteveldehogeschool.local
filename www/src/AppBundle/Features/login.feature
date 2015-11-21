# language: en
Feature: User login
  In order to access secured content of the website
  As a user
  I need to log in.

  Background:
    Given the users are:
      | username         | password          |
      | nmdad3_gebruiker | nmdad3_wachtwoord |

  @mink:default
  Scenario: Go to login page, but do not login
    Given I am on the homepage
      And print current URL
    When I follow "Login"
      Then I should be on "/security/login"
        And print current URL
    When I follow "Back"
      Then I should be on "/"
        And print current URL
      Then I should see "Login"
        And I should see "Register"

  @mink:default
  Scenario Outline: Try to log in with valid credentials
    Given I am on the homepage
      And print current URL
    When I follow "Login"
      Then I should be on "/security/login"
        And print current URL
    When I fill in "appbundle_security_login[username]" with "<username>"
      And I fill in "appbundle_security_login[password]" with "<password>"
      And I press "Login"
    Then I should be on "/"
      And print current URL
    Then I should see "Logout"
    But I should not see "Login"
      And I should not see "Register"
    When I follow "Logout"
      Then I should be on "/security/login"
        And print current URL
      Then I should see "Login"
        And I should see "Register"
      But I should not see "Logout"

    Examples:
      | username         | password          |
      | nmdad3_gebruiker | nmdad3_wachtwoord |

  @mink:default
  Scenario Outline: Try to in with invalid credentials
    Given I am on the homepage
      And print current URL
    When I follow "Login"
    Then I should be on "/security/login"
      And print current URL
    When I fill in "appbundle_security_login[username]" with "<username>"
      And I fill in "appbundle_security_login[password]" with "<password>"
      And I press "Login"
    Then I should be on "/security/login"
      And print current URL
    Then I should see "<message>"

    Examples:
      | username | password | message          |
      |          |          | Bad credentials. |
      | x        | x        | Bad credentials. |

# To see or find the predefined steps in the context (MinkContext), use:
# vagrant@homestead$ bin/behat -di
# vagrant@homestead$ bin/behat -dl

# To run only this test
# vagrant@homestead$ bin/behat @AppBundle/login.feature