  Feature:
    As a developer I want to get multiple covers by type and ID in specific image format(s),
    specific image size(s) and with or without generic covers.

    # We only create schema and add test data once because we have to do "wait(1)" after
    # adding search entries to give elasticsearch time to build the index

    @createSchema
    Scenario: Build and index test data
      Given the following search entries exits:
        | identifier              | type  | url                               | autogenerated | image_format |
        | 9788711829100           | isbn  | http://test.com/9788711829100.jpg | true          | jpeg         |
        | 9788711829101           | isbn  | http://test.com/9788711829101.jpg | true          | jpeg         |
        | 9788711829102           | isbn  | http://test.com/9788711829102.jpg | true          | jpeg         |
        | 55126216                | faust | http://test.com/55126216.jpg      | false         | jpeg         |
        | 65126216                | faust | http://test.com/55126216.jpg      | false         | jpeg         |
        | 75126216                | faust | http://test.com/75126216.jpg      | false         | jpeg         |
        | 870970-basis:52182794   | pid   | http://test.com/55126216.jpg      | false         | jpeg         |
        | 870970-katalog:52182794 | pid   | http://test.com/55126216.jpg      | false         | jpeg         |
        | 870970-basis:52182796   | pid   | http://test.com/55126216.jpg      | false         | jpeg         |
      And I send a "GET" request to "/api/cover/isbn/9788711829100"
      Then the response status code should be 200

    Scenario Outline: Get multiple covers by type and identifier
      Given I add "Accept" header equal to "application/json"
      And I send a "GET" request to "/api/cover/<type>?id=<identifiers>"
      Then the response status code should be 200
      And the header "Content-Type" should be equal to "application/json; charset=utf-8"
      And the response should be in JSON
      And the JSON node "[0].id" should be equal to "<identifier1>"
      And the JSON node "[1].id" should be equal to "<identifier2>"
      And the JSON node "[2].id" should be equal to "<identifier3>"
      And the JSON node "[0].type" should be equal to "<type>"
      And the JSON node "[1].type" should be equal to "<type>"
      And the JSON node "[2].type" should be equal to "<type>"
      And the JSON node "root" should have 3 elements

      Examples:
        | identifiers                                                         | type  | identifier1           | identifier2           | identifier3             |
        | 8788711829100,9788711829100,9788711829101,9788711829102             | isbn  | 9788711829100         | 9788711829101         | 9788711829102           |
        | 45126216,55126216,65126216,75126216                                 | faust | 55126216              | 65126216              | 75126216                |
        | 870970-basis:52182794,870970-katalog:52182794,870970-basis:52182796 | pid   | 870970-basis:52182794 | 870970-basis:52182796 | 870970-katalog:52182794 |

    Scenario Outline: Search for unknown covers should return an ampty list
      Given I add "Accept" header equal to "application/json"
      And I send a "GET" request to "/api/cover/<type>?id=<unknownIdentifiers>"
      Then the response status code should be 200
      And the header "Content-Type" should be equal to "application/json; charset=utf-8"
      And the response should be in JSON
      And the JSON node "root" should have 0 elements

      Examples:
        | unknownIdentifiers                                      | type  |
        | 9788711829200,9788711829201,9788711829202               | isbn  |
        | 8788711829100,9788711829100,9788711829101,9788711829102 | pid   |
        | 45126200,55126200,65126200,75126200                     | faust |
        | 970970-basis:52182794,970970-katalog:52182794           | faust |
