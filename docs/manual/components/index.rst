.. A index page for components documentation

Slick modules
=============

Slick framework is organized into modules that can perform a specific tasks.

For example the Cache module (``slick/cache`` in composer) is a module that can deal with cache
drivers such as Memcachd.

To use one of this modules you simply add the following line to your project's ``composer.json`` file:

.. code-block:: json
    :emphasize-lines: 3-5

    {
        "require": {
            "slick/cache": "1.0.*@dev",
            "slick/template": "1.0.*@dev",
            "slick/i18n": "1.0.*@dev",
            ...
        }
    }

List of Slick modules:

.. toctree::
    :maxdepth: 2

    common
    cache