<?php

/**
 * LICENSE: This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 *
 * @copyright 2016 Copyright(c) - All rights reserved.
 */

namespace Rafrsr\LibArray2Xml;

/**
 * Array2XML: A class to convert array in PHP to XML
 * It also takes into account attributes names unlike SimpleXML in PHP
 * It returns the XML in form of DOMDocument class for further manipulation.
 * It throws exception if the tag name or attribute name has illegal chars.
 *
 * Based on http://www.lalit.org/lab/convert-xml-to-array-in-php-xml2array/
 *
 *  - some minor bug fixes
 *  - support for php7
 *  - tests
 *
 * Usage:
 *       $xml = Array2XML::createXML('root_node_name', $php_array);
 *       echo $xml->saveXML();
 */
class Array2XML
{
    use CommonsTrait;

    /**
     * Convert an Array to XML
     *
     * @param string $nodeName - name of the root node to be converted
     * @param mixed  $data     - content to put into the xml
     *
     * @return \DOMDocument
     * @throws \InvalidArgumentException
     */
    public static function createXML($nodeName, $data)
    {
        $xml = self::getXMLRoot();
        $xml->appendChild(self::convert($nodeName, $data));

        self::$xml = null;// clear the xml node in the class for 2nd time use.

        return $xml;
    }

    /**
     * Convert an Array to XML
     *
     * @param string $nodeName - name of the root node to be converted
     * @param mixed  $data     - content to put into the xml
     *
     * @return \DOMNode
     * @throws \InvalidArgumentException
     */
    private static function convert($nodeName, $data)
    {
        //print_arr($nodeName);
        $xml = self::getXMLRoot();
        $node = $xml->createElement($nodeName);

        if (is_array($data)) {
            // get the attributes first.;
            if (array_key_exists(self::$prefixAttributes . 'attributes', $data)) {
                foreach ($data[self::$prefixAttributes . 'attributes'] as $key => $value) {
                    self::validateTagName($key, $nodeName, 'attribute');
                    $node->setAttribute($key, self::bool2str($value));
                }
                unset($data[self::$prefixAttributes . 'attributes']); //remove the key from the array once done.
            }

            // check if it has a value stored in @value, if yes store the value and return
            // else check if its directly stored as string
            if (array_key_exists(self::$prefixAttributes . 'value', $data)) {
                $node->appendChild($xml->createTextNode(self::bool2str($data[self::$prefixAttributes . 'value'])));
                unset($data[self::$prefixAttributes . 'value']);    //remove the key from the array once done.
                //return from recursion, as a note with value cannot have child nodes.
                return $node;
            } else {
                if (array_key_exists(self::$prefixAttributes . 'cdata', $data)) {
                    $node->appendChild($xml->createCDATASection(self::bool2str($data[self::$prefixAttributes . 'cdata'])));
                    unset($data[self::$prefixAttributes . 'cdata']);    //remove the key from the array once done.
                    //return from recursion, as a note with cdata cannot have child nodes.
                    return $node;
                }
            }
        }

        //create sub-nodes using recursion
        if (is_array($data)) {
            // recurse to get the node for that key
            foreach ($data as $key => $value) {
                self::validateTagName($key, $nodeName, 'tag');

                if (is_array($value) && reset($value) && is_numeric(key($value))) {
                    // MORE THAN ONE NODE OF ITS KIND;
                    // if the new array is numeric index, means it is array of nodes of the same kind
                    // it should follow the parent key name
                    foreach ($value as $k => $v) {
                        $node->appendChild(self::convert($key, $v));
                    }
                } else {
                    // ONLY ONE NODE OF ITS KIND
                    $node->appendChild(self::convert($key, $value));
                }
                unset($data[$key]); //remove the key from the array once done.
            }
        }

        // after we are done with all the keys in the array (if it is one)
        // we check if it has any text value, if yes, append it.
        if (!is_array($data)) {
            $node->appendChild($xml->createTextNode(self::bool2str($data)));
        }

        return $node;
    }

    /*
     * Get string representation of boolean value
     */
    private static function bool2str($v)
    {
        //convert boolean to text value.
        $v = $v === true ? 'true' : $v;
        $v = $v === false ? 'false' : $v;

        return $v;
    }

    /*
     * Check if the tag name or attribute name contains illegal characters
     * Ref: http://www.w3.org/TR/xml/#sec-common-syn
     *
     * @param string $tag      tag name to verify
     * @param string $nodeName node name for informational purposes on error
     * @param string $type     verify "tag" or "attribute"
     */
    private static function validateTagName($tag, $nodeName, $type = 'tag')
    {
        $pattern = '/^[a-z_]+[a-z0-9\:\-\.\_]*[^:]*$/i';

        if (!(preg_match($pattern, $tag, $matches) && $matches[0] === $tag)) {
            $msg = sprintf('[Array2XML] Illegal character in %s name. %s: %s in node: %s', $type, $type, $tag, $nodeName);

            throw new \InvalidArgumentException($msg);
        }
    }
}