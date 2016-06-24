# lib-array2xml

[![Build Status](https://travis-ci.org/rafrsr/lib-array2xml.svg?branch=master)](https://travis-ci.org/rafrsr/lib-array2xml)
[![Coverage Status](https://coveralls.io/repos/github/rafrsr/lib-array2xml/badge.svg?branch=master)](https://coveralls.io/github/rafrsr/lib-array2xml?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rafrsr/lib-array2xml/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rafrsr/lib-array2xml/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/rafrsr/lib-array2xml/version)](https://packagist.org/packages/rafrsr/lib-array2xml)
[![Latest Unstable Version](https://poser.pugx.org/rafrsr/lib-array2xml/v/unstable)](//packagist.org/packages/rafrsr/lib-array2xml)
[![Total Downloads](https://poser.pugx.org/rafrsr/lib-array2xml/downloads)](https://packagist.org/packages/rafrsr/lib-array2xml)
[![License](https://poser.pugx.org/rafrsr/lib-array2xml/license)](https://packagist.org/packages/rafrsr/lib-array2xml)

XML2Array is a class to convert XML to an array in PHP. It returns an array which can be converted back to XML using the Array2XML class.

It can take a string XML as input or an object of type DOMDocument.

## Installation

1. [Install composer](https://getcomposer.org/download/)
2. Execute: `composer require rafrsr/lib-array2xml`

## Usage

The usage is pretty simple. You have to include the class file in your code and call the following function.

````php
$array = XML2Array::createArray($xml);
print_r($array);
````

### Example

The Following XML:

````xml
<?xml version="1.0" encoding="UTF-8"?>
<movies type="documentary">
  <movie>
    <title>PHP: Behind the Parser</title>
    <characters>
      <character>
        <name>Ms. Coder</name>
        <actor>Onlivia Actora</actor>
      </character>
      <character>
        <name>Mr. Coder</name>
        <actor>El ActÓr</actor>
      </character>
    </characters>
    <plot><![CDATA[So, this language. It's like, a programming language. Or is it a scripting language? 
All is revealed in this thrilling horror spoof of a documentary.]]></plot>
    <great-lines>
      <line>PHP solves all my web problems</line>
    </great-lines>
    <rating type="thumbs">7</rating>
    <rating type="stars">5</rating>
  </movie>
</movies>
````

will generate the following output:

````php
array (
    'movies' => array (
        'movie' => array (
            'title' => 'PHP: Behind the Parser',
            'characters' => array (
                'character' => array (
                    0 => array (
                        'name' => 'Ms. Coder',
                        'actor' => 'Onlivia Actora',
                    ),
                    1 => array (
                        'name' => 'Mr. Coder',
                        'actor' => 'El ActÓr',
                    ),
                ),
            ),
            'plot' => array (
                '@cdata' => 'So, this language. It\'s like, a programming language. Or is it a scripting language? 
All is revealed in this thrilling horror spoof of a documentary.',
            ),
            'great-lines' => array (
                'line' => 'PHP solves all my web problems',
            ),
            'rating' => array (
                0 => array (
                    '@value' => '7',
                    '@attributes' => array (
                        'type' => 'thumbs',
                    ),
                ),
                1 => array (
                    '@value' => '5',
                    '@attributes' => array (
                        'type' => 'stars',
                    ),
                ),
            ),
        ),
        '@attributes' => array (
            'type' => 'documentary',
        ),
    ),
)
````

## References

This class is based on http://www.lalit.org/lab/convert-xml-to-array-in-php-xml2array/
with some minor bug fixed and support for php7

## Copyright

This project is licensed under the [MIT license](LICENSE).