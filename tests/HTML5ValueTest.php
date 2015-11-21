<?php
/**
 * @package framework
 * @subpackage tests
 */
class SS_HTML5ValueTest extends SapphireTest
{
    public function testInvalidHTMLParsing()
    {
        $value = new SS_HTML5Value();

        $invalid = array(
            '<p>Enclosed Value</p></p>'                              => '<p>Enclosed Value</p><p></p>',
            '<meta content="text/html"></meta>'                      => '<meta content="text/html">',
            '<p><div class="example"></div></p>'                     => '<p></p><div class="example"></div><p></p>'
        );
        
        foreach ($invalid as $input => $expected) {
            $value->setContent($input);
            $this->assertEquals($expected, $value->getContent(), 'Invalid HTML can be parsed');
        }
    }

    public function testUtf8Saving()
    {
        $value = new SS_HTML5Value();

        $value->setContent('<p>ö ß ā い 家</p>');
        $this->assertEquals('<p>ö ß ā い 家</p>', $value->getContent());
    }

    public function testWhitespaceHandling()
    {
        $value = new SS_HTML5Value();

        $value->setContent('<p></p> <p></p>');
        $this->assertEquals('<p></p> <p></p>', $value->getContent());
    }

    public function testInvalidHTMLTagNames()
    {
        $value = new SS_HTML5Value();

        $invalid = array(
            '<p><div><a href="test-link"></p></div>',
            '<html><div><a href="test-link"></a></a></html_>'
        );
        
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
        $value = new SS_HTML5Value();

        $value->setContent("<p>paragraph</p>\n<ul><li>1</li>\r\n</ul>");
        $this->assertEquals(
            "<p>paragraph</p>\n<ul><li>1</li>\n</ul>",
            $value->getContent(),
            'Newlines get converted'
        );
    }
}
