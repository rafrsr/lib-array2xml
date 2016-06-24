<?php

/**
 * LICENSE: This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 *
 * @copyright 2016 Copyright(c) - All rights reserved.
 */

namespace Rafrsr\LibArray2Xml\Tests;


use Rafrsr\LibArray2Xml\XML2Array;

class XML2ArrayTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateArray()
    {
        $xml = '<ROOT><node><child>string</child></node></ROOT>';
        $originArray = [
            'ROOT' => [
                'node' => [
                    'child' => 'string',
                ]
            ]
        ];

        $array = XML2Array::createArray($xml);
        self::assertEquals($originArray, $array);
    }
}
