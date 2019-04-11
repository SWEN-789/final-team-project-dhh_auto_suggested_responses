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


## How to run locally ##

To boot the service, run one of the following commands based on the respective operating system:

- `mvnw spring-boot:run` (Windows)
- `./mvnw spring-boot:run` (Linux, Unix, Macintosh)

NOTE: These commands run the service on the port 8080 as the default. If you need or would like to run the service on a different port, run one of the following commands based on the operating system:

- `mvnw spring-boot:run -Dserver.port=<portNumber>` (Windows)
- `./mvnw spring-boot:run -Dserver.port=<portNumber>` (Linux, Unix, Macintosh)

NOTE: these commands require the environment variable `JAVA_HOME` to be set up on your machine

If successful, some of the output of these commands should look like:

    
    2019-04-01 20:40:26.846  INFO 14636 --- [           main] o.s.b.w.embedded.tomcat.TomcatWebServer  : Tomcat started on port(s): 8080 (http) with context path ''
    2019-04-01 20:40:26.850  INFO 14636 --- [           main] e.rit.se.a11y.autoresponses.Application  : Started Application in 4.043 seconds (JVM running for 12.061)
    

There is also an endpoint that provides the health of the service after running, which is a GET resource at the following URL (when running locally on the default port 8080):

[http://localhost:8080/actuator/health](http://localhost:8080/actuator/health "Actuator Health")

This endpoint will return the following response if the service is up and running successfully:

```json
{ status: 'UP' }
```


## How to deploy the service to Heroku ##

Follow the instructions in the link below:

[https://www.callicoder.com/deploy-host-spring-boot-apps-on-heroku/#deploying-a-spring-boot-app-on-heroku-using-heroku-maven-plugin](https://www.callicoder.com/deploy-host-spring-boot-apps-on-heroku/#deploying-a-spring-boot-app-on-heroku-using-heroku-maven-plugin "Deploying a Sprint Boot App on Heroku Using Maven Plugin")

NOTE: use `mvnw` instead of `mvn` for this service
NOTE: the mvnw commands require the environment variable `JAVA_HOME` to be set up on your machine

If successful, the output of the `mvnw clean heroku:deploy` command should look like:

    
    [INFO] -----> Packaging application...
    [INFO]        - app: dhh-service
    [INFO]        - including: target/AutoSuggestedResponsesService-0.0.1-SNAPSHOT.jar
    [INFO] -----> Creating build...
    [INFO]        - file: target/heroku/build.tgz
    [INFO]        - size: 15MB
    [INFO] -----> Uploading build...
    [INFO]        - success
    [INFO] -----> Deploying...
    [INFO] remote:
    [INFO] remote: -----> heroku-maven-plugin app detected
    [INFO] remote: -----> Installing JDK 1.8... done
    [INFO] remote: -----> Discovering process types
    [INFO] remote:        Procfile declares types -> web
    [INFO] remote:
    [INFO] remote: -----> Compressing...
    [INFO] remote:        Done: 66.4M
    [INFO] remote: -----> Launching...
    [INFO] remote:        Released v3
    [INFO] remote:        https://dhh-service.herokuapp.com/ deployed to Heroku
    [INFO] remote:
    [INFO] -----> Done
    [INFO] ------------------------------------------------------------------------
    [INFO] BUILD SUCCESS
    [INFO] ------------------------------------------------------------------------
    [INFO] Total time:  02:38 min
    [INFO] Finished at: 2019-04-10T22:36:28-04:00
    [INFO] ------------------------------------------------------------------------
    

To determine if the service is up and running successfully on Heroku, use the following endpoint (using the endpoint from the sample output above):

[https://dhh-service.herokuapp.com/actuator/health](https://dhh-service.herokuapp.com/actuator/health "Actuator Health on Heroku")

This endpoint will return the following response if the service is up and running successfully:

```json
{ status: 'UP' }
```

If you get an "Application error" screen, you can check the Heroku app's error logs by running the following command (where <APP-NAME> would be replaced with the name of the app specified in the pom.xml file, which would be dhh-service):

```
heroku logs --tail --app <APP-NAME>
```

NOTE: if you need to re-use the "https://dhh-service.herokuapp.com" endpoint, please check with a developer on this repo, as the Heroku deploy command will overwrite the current version hosted in Heroku. If you don't need to re-use this specifically-named endpoint, you may substitute the app-name "dhh-service" with another app-name that hasn't been used yet.