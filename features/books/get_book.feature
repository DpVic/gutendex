Feature: Get book by ID
    In order to see the details of a specific book
    As an API user
    I want to be able to retrieve a book by its ID

    Scenario: Get an existing book
        When I send a GET request to "/api/books/1"
        Then the response status code should be 200
        And the response should be in JSON
        And the response should contain a book with title "The King James Bible"

    Scenario: Get a non-existing book
        When I send a GET request to "/api/books/99999999"
        Then the response status code should be 404
        And the response should be in JSON
        And the response should contain an error message "Book not found"
