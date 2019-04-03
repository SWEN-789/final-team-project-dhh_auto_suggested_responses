# AutoSuggestedResponsesService

This service serves as the back-end component for providing functionality related to automatic suggested responses based on a conversation subject and preceding message.

## POST /get-suggested-responses ##
Gets a list of suggested responses based on the specified context and preceding message.

Request body required; should be in the format like the following example:

```json
{
    "context": "food service",
    "message": "what would you like to order"
}
```

Response should like something like:

```json
{
    "suggestedResponses": [
        "Could I please get a cheeseburger?",
        "Could I please get a chicken sandwich?"
    ]
}
```


## POST /add-suggested-response ##
Adds a suggested response to the list of suggestions for a particular context.

Request body required; should be in the format like the following example:

```json
{
    "context": "food service",
    "suggestedResponse": "Could I please get a salad?"
}
```

No response body; should return HTTP status code 200 if successful


## How to run ##

To boot the service, run one of the following commands based on the respective operating system:

- `mvnw spring-boot:run` (Windows)
- `./mvnw spring-boot:run` (Linux, Unix, Macintosh)

NOTE: These commands run the service on the port 8080 as the default. If you need or would like to run the service on a different port, run one of the following commands based on the operating system:

- `mvnw spring-boot:run -Dserver.port=<portNumber>` (Windows)
- `./mvnw spring-boot:run -Dserver.port=<portNumber>` (Linux, Unix, Macintosh)

NOTE: these commands require the environment variable `JAVA_HOME` to be set up on your machine

If successful, some of the output of these commands should look like:

> 2019-04-01 20:40:26.846  INFO 14636 --- [           main] o.s.b.w.embedded.tomcat.TomcatWebServer  : Tomcat started on port(s): 8080 (http) with context path ''

> 2019-04-01 20:40:26.850  INFO 14636 --- [           main] e.rit.se.a11y.autoresponses.Application  : Started Application in 4.043 seconds (JVM running for 12.061)

There is also an endpoint that provides the health of the service after running, which is a GET resource at the following URL (when running locally on the default port 8080):

[http://localhost:8080/actuator/health](http://localhost:8080/actuator/health "Actuator Health")

This endpoint will return the following response if the service is up and running successfully:

```json
{ status: 'UP' }
```