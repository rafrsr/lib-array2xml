<?php

/**
 * LICENSE: This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 *
 * @copyright 2016 Copyright(c) - All rights reserved.
 */

namespace Rafrsr\LibArray2Xml\Tests;

use Rafrsr\LibArray2Xml\Array2XML;
use Rafrsr\LibArray2Xml\XML2Array;

class Array2XMLTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateXML()
    {
        $test = [
            'node' => [
                'child' => 'string',
            ]
        ];

        $xml = Array2XML::createXML('ROOT', $test);
        self::assertEquals('<ROOT><node><child>string</child></node></ROOT>', preg_replace('/\s/', null, $xml->saveHTML()));
    }

    public function testToArrayToXML()
    {
        $sampleFile = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'sample.xml');

        $xmlArray = XML2Array::createArray($sampleFile);
        $xml = Array2XML::createXML('ROOT', $xmlArray);
        $xml = $xml->saveXML($xml->firstChild->firstChild);

        //remove spaces for comparison
        $sampleFile = preg_replace('/(>)\s+/', null, $sampleFile);
        $xml = preg_replace('/(>)\s+/', null, $xml);

        self::assertEquals($sampleFile, $xml);
    }
}
