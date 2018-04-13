<?php

/**
 * LICENSE: This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 *
 * @copyright 2016 Copyright(c) - All rights reserved.
 */

namespace Rafrsr\LibArray2Xml;

/**
 * XML2Array: A class to convert XML to array in PHP
 * It returns the array which can be converted back to XML using the Array2XML script
 * It takes an XML string or a DOMDocument object as an input.
 *
 * Based on http://www.lalit.org/lab/convert-xml-to-array-in-php-xml2array/
 *
 *  - some minor bug fixes
 *  - support for php7
 *  - tests
 *
 * Usage:
 *       $array = XML2Array::createArray($xml);
 */
class XML2Array
{
    use  CommonsTrait;

    /**
     * Convert an XML to Array
     *
     * @param string $inputXml - xml to convert
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public static function createArray($inputXml)
    {
        $xml = self::getXMLRoot();
        if (is_string($inputXml)) {
            $parsed = $xml->loadXML($inputXml);
            if (!$parsed) {
                throw new \InvalidArgumentException('[XML2Array] Error parsing the XML string.');
            }
        } else {
            if (get_class($inputXml) !== 'DOMDocument') {
                throw new \InvalidArgumentException('[XML2Array] The input XML object should be of type: DOMDocument.');
            }
            $xml = self::$xml = $inputXml;
        }
        $array = [];
        $array[$xml->documentElement->tagName] = self::convert($xml->documentElement);
        self::$xml = null;// clear the xml node in the class for 2nd time use.

        return $array;
    }

    /**
     * Convert an Array to XML
     *
     * @param mixed $node - XML as a string or as an object of DOMDocument
     *
     * @return mixed
     */
    private static function convert($node)
    {
        $output = [];

        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
                $output[self::$prefixAttributes . 'cdata'] = trim($node->textContent);
                break;

            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;

            case XML_ELEMENT_NODE:
                // for each child node, call the covert function recursively
                for ($i = 0, $m = $node->childNodes->length; $i < $m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v = self::convert($child);
                    if (isset($child->tagName)) {
                        $t = $child->tagName;

                        // assume more nodes of same kind are coming
                        if (!array_key_exists($t, $output)) {
                            $output[$t] = [];
                        }
                        $output[$t][] = $v;
                    } else {
                        //check if it is not an empty text node
                        if ($v !== '') {
                            $output = $v;
                        }
                    }
                }

                if (is_array($output)) {
                    // if only one node of its kind, assign it directly instead if array($value);
                    foreach ($output as $t => $v) {
                        if (is_array($v) && count($v) === 1) {
                            $output[$t] = $v[0];
                        }
                    }
                    if (count($output) === 0) {
                        //for empty nodes
                        $output = '';
                    }
                }

                // loop through the attributes and collect them
                if ($node->attributes->length) {
                    $a = [];
                    foreach ($node->attributes as $attrName => $attrNode) {
                        $a[$attrName] = (string)$attrNode->value;
                    }
                    // if its an leaf node, store the value in @value instead of directly storing it.
                    if (!is_array($output)) {
                        $output = [self::$prefixAttributes . 'value' => $output];
                    }
                    $output[self::$prefixAttributes . 'attributes'] = $a;
                }
                break;
        }

        return $output;
    }
}