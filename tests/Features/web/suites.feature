Feature: User can see suites

    Scenario: User can see list of suites
        When I visit "/"
         Then the response status code should be 200
