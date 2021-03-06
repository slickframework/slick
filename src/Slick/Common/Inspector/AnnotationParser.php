<?php

/**
 * Annotation Parser
 *
 * @package    Slick\Common\Inspector
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.1.0
 */

namespace Slick\Common\Inspector;

/**
 * Parses doc block comments to retrieve the annotations values
 *
 * @package    Slick\Common\Inspector
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class AnnotationParser
{

    /**#@+
     * @var string Annotation regular expression
     * @codingStandardsIgnoreStart
     */
    const ANNOTATION_REGEX = '/@(\w+)(?:\s*(?:\(\s*)?(.*?)(?:\s*\))?)??\s*(?:\n|\*\/)/';
    const ANNOTATION_PARAMETERS_REGEX = '/([\w]+\s*=\s*[\[\{"]{1}[\w,\\\\\s:\."\{\[\]\}]+[\}\]""]{1})|([\w]+\s*=\s*[\\\\\w\.]+)|([\\\\\w]+)/i';
    /**#@-*/
    // @codingStandardsIgnoreEnd

    /**
     * Retrieves a list of annotations within the provided comment.
     *
     * @param string $comment
     *
     * @return string[]
     */
    public static function getAnnotations($comment)
    {
        $hasAnnotations = preg_match_all(
            self::ANNOTATION_REGEX,
            $comment,
            $matches,
            PREG_SET_ORDER
        );

        if (!$hasAnnotations) {
            return [];
        }

        $annotations = [];

        foreach ($matches as $annotation) {
            $name = $annotation[1];

            $value = true;
            if (isset($annotation[2])) {
                if (
                    preg_match_all(
                        static::ANNOTATION_PARAMETERS_REGEX,
                        $annotation[2],
                        $result
                    )
                ) {
                    $value = [];
                    foreach ($result[0] as $part) {
                        $param = static::_getNameValuePair($part);
                        $value[$param['name']] = $param['value'];
                    }
                }
                $value['_raw'] = trim($annotation[2]);
            }
            $annotations[$name] = $value;
        }

        return $annotations;
    }

    /**
     * Splits the parameter using the first equal "=" sign
     *
     * @param string $part
     * @return array
     */
    private static function _getNameValuePair($part)
    {
        $parts = explode("=", $part, 2);
        $pair = ['name' => $parts[0], 'value' => true];
        if (isset($parts[1])) {
            $pair = [
                'name' => $parts[0],
                'value'=> static::_parseValue($parts[1])
            ];
        }
        return $pair;
    }

    /**
     * Parses the value of a provided param
     *
     * @param string $value
     * @return array|bool|float|int|mixed|string
     */
    private static function _parseValue($value)
    {
        $val = trim(trim($value), '"');

        if (substr($val, 0, 1) == '[' && substr($val, -1) == ']') {
            // Array values
            $values = explode(',', substr($val, 1, -1));
            $val = array();
            foreach ($values AS $v) {
                $val[] = self::_parseValue($v);
            }
            return $val;

        } else if (substr($val, 0, 1) == '{' && substr($val, -1) == '}') {
            // If is json object that start with { } decode them
            return json_decode($val);
        } else if (strtolower($val) == 'true') {
            // Boolean value = true
            return true;

        } else if (strtolower($val) == 'false') {
            // Boolean value = false
            return false;

        } else if (is_numeric($val)) {
            return static::_checkNumberValue($val);
        }

        // Nothing special, just return as a string
        return $val;
    }

    /**
     * Numeric value, determine if int or float and then cast
     *
     * @param mixed $val
     * @return float|int
     */
    private static function _checkNumberValue($val)
    {
        if ((float) $val == (int) $val) {
            return (int) $val;
        }
        return (float) $val;
    }
}
