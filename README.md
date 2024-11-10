# Accepted Assignment - Spies API

## High-Level Architecture

The Spies API is a RESTful service built with Laravel 11 using PHP 8.3, Apache and MySQL, following Domain-Driven
Design (DDD) and CQRS design principles.

The architecture consists of the following layers:

- **Domain Layer**: Encapsulates core business logic and entities, such as value objects, models, domain events and
  repository contracts.
- **Application Layer**: Manages application-specific logic, including commands and actions for handling spy-related
  operations, queries for data retrieval and DTOs as well as needed contracts.
- **Infrastructure Layer**: Includes persistence, controllers and event related listeners and jobs.
- **Dockerized Environment**: The application is containerized using Docker, making setup and deployment consistent
  across environments, available in the docker folder. It contains 2 services, the app and a mysql database. An env is
  used for parameterization and an apache is used for handling the requests. Upon building, migrations and seeders are
  run.
- **Tests**: Tests are includes for features and units using PHPUnit.

## SETUP

- First install docker according to your system (Windows, Linux, etc)
    - see more here: https://docs.docker.com/engine/install/
- Then open a terminal and `cd docker` to the docker directory
- Run `docker-compose up --build` to build and run and see logs of the containers
    - or `docker-compose up --build -d` to build and run the containers detached. Then, use `docker-compose logs -f` to
      view the logs

## USAGE

- Cloen the repository with `git clone https://github.com/dounasth/accepted-spy-test.git`
- Go to the `./docker` folder
- Start the docker containers with `docker-compose up --build` or `docker-compose up` if already built
- To run the tests in the docker, from the docker folder run
  `docker-compose exec accepted-spy-test-app php artisan test`
    - the tests refresh the database and run various seeds or factories according to their needs
- A postman collection is included that can be used to test the container with postman.
    - There are 4 collection variables:
        - host: set this to http://localhost:8080
        - email: set this to dounasth@gmail.com, it is hardcoded for ease of testing
        - password: set this to accepted_test, also hardcoded
        - user_token: can either be set by executing the `/api/login` endpoint or will be automatically set by a
          pre-request script for the spy creation endpoint.
