<?php

namespace SilverStripe\HTML5\Tests;

use SilverStripe\Dev\SapphireTest;
use SilverStripe\HTML5\HTML5Value;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\View\Parsers\ShortcodeParser;

/**
 * @package framework
 * @subpackage tests
 */
class HTML5ValueTest extends SapphireTest
{
    public function testInvalidHTMLParsing()
    {
        $value = new HTML5Value();

        $invalid = [
            '<p>Enclosed Value</p></p>'          => '<p>Enclosed Value</p>',
            '<p><div class="example"></div></p>' => '<p></p><div class="example"></div>'
        ];

        foreach ($invalid as $input => $expected) {
            $value->setContent($input);
            $this->assertEquals($expected, $value->getContent(), 'Invalid HTML can be parsed');
        }
    }

    public function testUtf8Saving()
    {
        $value = new HTML5Value();

        $value->setContent('<p>ö ß ā い 家</p>');
        $this->assertEquals('<p>ö ß ā い 家</p>', $value->getContent());
    }

    public function testWhitespaceHandling()
    {
        $value = new HTML5Value();

        $value->setContent('<p></p> <p></p>');
        $this->assertEquals('<p></p> <p></p>', $value->getContent());
    }

    public function testInvalidHTMLTagNames()
    {
        $value = new HTML5Value();

        $invalid = [
            '<p><div><a href="test-link"></p></div>',
            '<html><div><a href="test-link"></a></a></html_>'
        ];

        foreach ($invalid as $input) {
            $value->setContent($input);

            $this->assertEquals(
                'test-link',
                $value->getElementsByTagName('a')->item(0)->getAttribute('href'),
                'Link data can be extraced from malformed HTML'
            );
        }
    }

    public function testMixedNewlines()
    {
        $value = new HTML5Value();

        $value->setContent("<p>paragraph</p>\n<ul><li>1</li>\r\n</ul>");
        $this->assertEquals(
            "<p>paragraph</p>\n<ul><li>1</li>\n</ul>",
            $value->getContent(),
            'Newlines get converted'
        );
    }

    public function testShortcodeValue()
    {
        ShortcodeParser::get('default')->register(
            'test_shortcode',
            function () {
                return 'bit of test shortcode output';
            }
        );
        $content = DBHTMLText::create('Test', ['shortcodes' => true])
            ->setValue('<p>Some content with a [test_shortcode] and a <br /> followed by an <hr> in it.</p>')
            ->forTemplate();
        $this->assertContains(
            // hr is flow content, not phrasing content, so must be corrected to be outside the p tag.
            '<p>Some content with a bit of test shortcode output and a <br> followed by an </p><hr> in it.',
            $content
        );
    }
}
