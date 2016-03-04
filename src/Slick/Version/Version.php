<?php

/**
 * Version
 *
 * @package    Slick\Version
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Slick\Version;

/**
 * Version
 *
 * @package    Slick\Version
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
final class Version
{
    /**
     * @var string Slick Framework version identification
     * @see \Slick\Version::compare()
     */
    const VERSION = '1.1.0-dev';

    /**
     * @var string The latest stable version available
     */
    protected static $_latestVersion;

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
        if (is_null(static::$_latestVersion)) {
            static::$_latestVersion = 'not available';
            $url = "https://api.github.com/repos/slickframework/slick/git/refs/tags/v";

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url,
                CURLOPT_USERAGENT => 'Slick Version Request'
            ));
            $data = curl_exec($curl);

            if ($data) {
                $apiResponse = json_decode($data, true);

                // Simplify the API response into a simple array of version numbers
                $tags = array_map(function ($tag) {
                    return substr($tag['ref'], 11);
                }, $apiResponse);

                // Fetch the latest version number from the array
                static::$_latestVersion = array_reduce($tags, function ($a, $b) {
                    return version_compare($a, $b, '>') ? $a : $b;
                });
            }
        }
        return static::$_latestVersion;
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
} 