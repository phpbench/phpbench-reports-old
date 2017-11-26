Feature: Post a suite
    As a developer
    In order to be able to ananalyze my benchmark results over time
    I want to be able to submit my results to Phpbench Reports

    Scenario: Submit suite
        When I submit the "variant1.json" payload to the variants endpoint
        Then the response status code should be 200

    Scenario: Submit iterations
        When I submit the "iterations1.json" payload to the iterations endpoint
        Then the response status code should be 200