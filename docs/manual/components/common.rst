.. Common library

Common library
==============

Overview
--------

Common library is a set of useful classes and traits that are in almost every class
in the entire Slick framework. They form a solid base to develop on top of and help
you remove the tedious work of create getters, setters, allow read end or write access
to the properties and inspect classes and properties.


Understanding the "Base" class
------------------------------

The ``Slick\Common\Base`` class is on of the most important classes of Slick. It is responsible for the
"magic" in classes that extends it. It is very important that you have a clear understanding of what
this class does to really speed up your development.

Defining class properties
~~~~~~~~~~~~~~~~~~~~~~~~~

When defining properties in classes that extend ``Slick\Common\Base`` you have to follow a simple convention:

* properties must be prefixed with an "_" (underscore);
* property visibility is defined as ``protected``;
* property names are camel cased;
* use one of ``@read``, ``@write`` or ``@readwrite`` notations to define the public access mode to those properties.

Lets see an example:

.. code-block:: php

    <?php

    use Slick\Common\Base;

    class MyClass extends Base
    {

        /**
         * @readwrite
         * @var string
         */
        protected $_name;

        ...
    }

    $myClass = new MyClass();

    $myClass->name = "Foo";
    echo $myClass->name;        // This will print out "Foo"


As you can see in this example, defining property ``MyClass::$_name`` is in fact very simple. The ``@readwrite``
notation says that it has read/write access to it and it is a protected property.

Did you notice the way we access the ``MyClass::$_name``? Yes, we access it as it was a ``public`` property!

This is the first feature of ``Slick\Common\Base``: to expose this properties in a ``public`` fashion
using the PHP ``__get()`` and ``__set()`` magic methods.

The notations used are defined as followed:

* ``@read`` The property can be accessed as a read only. Any attempt to assign a value to a read only property will raise a ``Slick\Common\Exception\ReadOnlyException`` exception. This is the default behavior if no notation is provided;
* ``@write`` A value can be assigned to the property. Any attempt to read the property value will raise a ``Slick\Common\Exception\WriteOnlyException`` exception;
* ``@readwrite`` The property can be read or changed as a ``public`` property.


.. note::

    This feature can be also achieved using ``private`` as visibility, however it will not work with objects
    of classes that extend the one using ``private`` visibility as it cannot be accessed by them.

.. warning::

    Any attempt to assign or read an undefined property on classes that extends ``Slick\Common\Base`` will
    raise a ``Slick\Common\Exception\UndefinedPropertyException`` exception. This is a great way to avoid
    dynamic change of objects.


Getters and Setters
~~~~~~~~~~~~~~~~~~~


