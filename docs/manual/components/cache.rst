.. Cache module

Cache
=====

Overview
--------
Slick cache module deals with cache providing services installed on your system.

I comes with support for *Memcached* (``memcached`` daemon) and *File* (caching data into files) out of the box,
but it defines a driver interface that allow you to add your own drivers to your application.

Using a cache driver
--------------------
Using a cache driver is very easy. You simple need to call the Cache::get() static method
to get an initialized cache driver.

Lets look at the following example:

.. code-block:: php
    :emphasize-lines: 3,7,8,11

    <?php

    use Slick\Cache\Cache;

    function getApiData()
    {
        $cache = Cache::get();
        $data = $cache->get('data', false);
        if (!$data) {
            $data = file_get_contents("http://www.example.com/api/call.json");
            $cache->set('data', $data);
        }
        return $data;
    }

In this example we are using the default cache driver with default options, to store some expensive API call data.

Changing cache expire time
~~~~~~~~~~~~~~~~~~~~~~~~~~
To set the expire time for cached data you can as follows:

.. code-block:: php
    :emphasize-lines: 8,9

    <?php

    use Slick\Cache\Cache;

    function getApiData()
    {
        $cache = Cache::get();
        // cache duration in seconds
        $cache->duration = 240;
        $data = $cache->get('data', false);
        ...
        return $data;
    }

This will set the cached data life time for all the Driver::get() calls.

You can set a per call specific cache duration like this:

.. code-block:: php
    :emphasize-lines: 8,9

    <?php

    use Slick\Cache\Cache;

    function getApiData()
    {
        $cache = Cache::get();
        // call to get data with duration
        $data = $cache->get('data', false, 240);
        ...
        return $data;
    }



The File cache driver
---------------------
The File cache driver uses the file system to store the cached driver. So for every data key that you want
to store in cache it will create a file with it.

.. note::

    Storing cache data into files is not the best way of doing cache and therefor you should consider
    using a better driver like Memcached.

    This driver was created for those situations where you don't have access to your server configuration
    and still want to cache *expensive* resources.

Lets look at the following example:

.. code-block:: php
    :emphasize-lines: 7

    <?php

    use Slick\Cache\Cache;

    function getApiData()
    {
        $cache = Cache::get('file', ['path' => './tmp/']);
        $data = $cache->get('data', false);
        if (!$data) {
            $data = file_get_contents("http://www.example.com/api/call.json");
            $cache->set('data', $data);
        }
        return $data;
    }

In this case we are setting a different path where we want to save our cache files.

The Memcached cache driver
--------------------------
The Memcached cache driver uses the `memcached <http://www.memcached.org/>`_, an high-performance,
distributed memory object caching system.

Memcached is an in-memory key-value store for small chunks of arbitrary data (strings, objects) from
results of database calls, API calls, or page rendering.

.. warning::

    You must have the Memcached PECL extension installed on your system to be able to use the
    Memcached cached diver provided by Slick.

    If you need help on have your system installed with Memcached extention please visit the
    `PHP Memcacded manual page <http://www.php.net/manual/en/memcached.installation.php>`_ for
    more information.

To use this driver, as we already saw before, you need to call the Cache::get() static method
and pass the driver name and options.

The following example illustrates a possible way of doing it:

.. code-block:: php
    :emphasize-lines: 8

    <?php

    use Slick\Cache\Cache;

    $cache = Cache::get('memcached', [
        'host' => '0.0.0.0',
        'port' => '11211',
        'duration' => 300 // 5 minutes
    ]);

    ...


Here we pass the host and port of the memcached daemon for PHP Memcache to connect.

If you were paying attention to the last code block you will notice that we add the ``duration`` param
to the diver initialization. It is possible to do that on all cache drivers.


