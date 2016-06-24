<?php

/**
 * LICENSE: This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 *
 * @copyright 2016 Copyright(c) - All rights reserved.
 */

namespace Rafrsr\LibArray2Xml;

trait CommonsTrait
{
    /**
     * @var \DOMDocument|null
     */
    private static $xml = null;

    /**
     * @var string
     */
    private static $encoding = 'UTF-8';

    /**
     * @var string
     */
    private static $prefixAttributes = '@';

    /**
     * Initialize the root XML node [optional]
     *
     * @param $version
     * @param $encoding
     * @param $formatOutput
     */
    public static function init($version = '1.0', $encoding = 'UTF-8', $formatOutput = true)
    {
        self::$xml = new \DOMDocument($version, $encoding);
        self::$xml->formatOutput = $formatOutput;
        self::$encoding = $encoding;
    }

    /*
    * Get the root XML node, if there isn't one, create it.
    */
    private static function getXMLRoot()
    {
        if (self::$xml === null) {
            self::init();
        }

        return self::$xml;
    }
}