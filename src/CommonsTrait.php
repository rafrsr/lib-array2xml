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
    private static ?\DOMDocument $xml = null;

    private static string $encoding = 'UTF-8';

    private static string $prefixAttributes = '@';

    /**
     * Initialize the root XML node [optional]
     */
    public static function init(string $version = '1.0', string $encoding = 'UTF-8', bool $formatOutput = true): void
    {
        self::$xml = new \DOMDocument($version, $encoding);
        self::$xml->formatOutput = $formatOutput;
        self::$encoding = $encoding;
    }

    /**
     * Get the root XML node, if there isn't one, create it.
     */
    private static function getXMLRoot(): \DOMDocument
    {
        if (self::$xml === null) {
            self::init();
        }

        return self::$xml;
    }
}