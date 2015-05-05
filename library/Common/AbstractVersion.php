<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common;


/**
 * Version concept used for version information on a package
 *
 * @package Slick\Common
 */
abstract class AbstractVersion
{

    /**
     * @var string Version identification
     *
     * @see \Slick\Common\AbstractVersion::compare()
     */
    const VERSION = 'UNDEFINED';

    /**
     * @var string GitHub URL
     */
    const REPOSITORY_URL = "";

    /**
     * @var string The latest stable version available
     */
    protected static $latestVersion;


    /**
     * Compare the provided version with current Slick version
     *
     * @param string $version
     *
     * @return int -1 if the $version is older,
     * 0 if they are the same,
     * and +1 if $version is newer.
     */
    public static function compare($version)
    {
        $version = strtolower($version);
        $version = preg_replace('/(\d)pr(\d?)/', '$1a$2', $version);
        return version_compare($version, strtolower(static::VERSION));
    }

    /**
     * Fetches the version of the latest stable release.
     *
     * @return string
     */
    public static function getLatest()
    {
        if (is_null(static::$latestVersion)) {
            $data = self::getRepositoryTags();
            if ($data) {
                $apiResponse = json_decode($data, true);
                // Simplify the API response into a simple array of version
                // numbers
                $tags = array_map(function($tag) {
                    return substr($tag['ref'], 11);
                }, $apiResponse);
                // Fetch the latest version number from the array
                static::$latestVersion = array_reduce(
                    $tags,
                    function($first, $second) {
                        return version_compare($first, $second, '>')
                            ? $first
                            : $second;
                    }
                );
            }
        }
        return static::$latestVersion;
    }

    /**
     * Returns true if the running version of Slick Framework is
     * the latest (or newer??) than the latest tag on GitHub,
     * which is returned by static::getLatest().
     *
     * @return bool
     */
    public static function isLatest()
    {
        return static::compare(static::getLatest()) < 1;
    }

    /**
     * Uses curl to get the repository tags
     *
     * @return string
     */
    protected static function getRepositoryTags()
    {
        static::$latestVersion = 'not available';
        $url = static::REPOSITORY_URL;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'Slick Version Request'
        ));
        return curl_exec($curl);
    }
}