Feature: Search books
    In order to find books by a search term
    As an API user
    I want to be able to search for books

    Scenario: Search for books by title
        When I send a GET request to "/api/books?search=Shakespeare"
        Then the response status code should be 200
        And the response should be in JSON
        And the response should contain a list of books
        And the first book should have title "Hamlet"

    Scenario: Search for books with no results
        When I send a GET request to "/api/books?search=NonExistingBookSearch"
        Then the response status code should be 200
        And the response should be in JSON
        And the response should be an empty list
