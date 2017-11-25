Feature: User can see suites

    Scenario: User can see list of suites
        When I visit "/"
        Then I should see a list of suites
