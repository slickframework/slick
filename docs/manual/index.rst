.. Slick Framework Documentation documentation master file, created by
   sphinx-quickstart on Sat Apr  5 15:53:26 2014.
   You can adapt this file completely to your liking, but it should at least
   contain the root `toctree` directive.

Welcome to Slick Framework Documentation's documentation!
=========================================================

What is Slick?
--------------
| Slick is a PHP 5.4+ MVC framework and tool set that aim to be simple robust and cool to work with.
| The goal was to create a PHP framework that could be used to develop web applications with agile methodologies like SCRUM or KANBAN in mind.

This is the documentation for the Slick Framework project.

Getting started
---------------
| The best way to get started with Slick framework is using composer. We have created a template that get you with a base web application files and directory structure.
| To start a project using our web application template run

.. code-block:: bash

    $ composer create-project slick/webapp <your-app-name>

| If you wat to use a Slick module in your existing project just add the corespondent module name your project ``composer.json`` file.
| Lets have an example. You want to use the ``slick/template`` Slick module to your project. What you need to do is add the following line to your ``composer.json``

.. code-block:: json
    :emphasize-lines: 3

    {
        "require": {
            "slick/template": "1.0.*@dev",
            ...
        }
    }

Then you need to run:

.. code-block:: bash

    $ composer update

to be able to add the specified library to your vendor directory.

A 15 minutes tutorial
---------------------
We have a simple tutorial that can get you an overview of all slick potential on making web applications.

.. todo:add a link to the tutorial


Table of contents
-----------------

.. toctree::
   :maxdepth: 2

   mvc/index
   components/index



.. MVC - Web application
    - Models
    - Controllers
    - Views
    - I18n
    - Adding your own libraries
    - Configuration
    - routing
    - Tests
    Slick Modules
    - Andustanding base class
    - cache
    - configuration
    - Database
    - Dependency injector
    - File System
    - Filter
    - Form
    - I18n
    - Logging
    - Object relational mapping
    - Session
    - Template
    - Utilities
    - Validators

Indices and tables
------------------

* :ref:`genindex`
* :ref:`modindex`
* :ref:`search`

