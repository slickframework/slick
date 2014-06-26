Slick Framework
===============

Slick is a PHP 5.4+ MVC framework and tool set that aim to be simple
robust and cool to work with. The goal was to create a PHP framework
that could be used to develop web applications with agile methodologies
like SCRUM or KANBAN in mind.

**Current build status**
[![Build Status](https://travis-ci.org/slickframework/slick.svg?branch=feature/dba)](https://travis-ci.org/slickframework/slick)

**Features**

> -   Cache management
> -   Easy configuration
> -   Dependency Injection container
> -   Session handling
> -   Form building (and rendering)
> -   Simple ORM that uses PDO for data access
> -   Data filters and validators
> -   Uses [Twig][] for a robust template engine
> -   Behavior/test driven development (with [Codeception][])
> -   A a lot of interfaces for easy implementations of your own needs.

**Installation**

The best way to get started with Slick framework is using composer. We
have created a template that get you with a base web application files
and directory structure. To start a project using our web application
template run

    $ composer create-project slick/webapp <your-app-name>

If you wat to use a Slick module in your existing project just add the
corespondent module name your project `composer.json` file. Lets have an
example. Adding the `slick/template` Slick module to your project is as
simple as adding the following line to your project’s `composer.json`
file:

    {
        "require": {
            "slick/template": "1.0.*@dev",
            ...
        }
    }

Then you need to run:

    $ composer update

to be able to add the specified library to your vendor directory.

**Contribute**

-   Issue Tracker: <https://github.com/slickframework/slick/issues>
-   Source Code: <https://github.com/slickframework/slick>

**Support**

If you are having issues, please let us know.

**License**

The project is licensed under the MIT License (MIT)

  [Twig]: http://twig.sensiolabs.org/
  [Codeception]: http://codeception.com/
