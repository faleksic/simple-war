# Simple war

This PHP app simulates war between two armies

## Description

The app is used by sending two GET parameters to the app root route. Parameters are `army1` and `army2`. 
URL example would look like this `http://localhost:8000?army1=50&army2=50`. After the program executes, you will be given an execution log displayed on the page.

## Getting Started

### Dependencies

* PHP v7.1 or higher
* [Composer](https://getcomposer.org/download/)
* [Symfony client](https://github.com/symfony/cli)

### Executing program

* I recommend using Symfony local web server, you can start it by getting to the project root and executing `./bin/console server:run`
* Going to this link `http://localhost:8000?army1=50&army2=50`, you can of course change the parameters to be the numbers you want.
