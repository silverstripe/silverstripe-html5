<?php

namespace SilverStripe\HTML5;

use Exception;
use Masterminds\HTML5;
use SilverStripe\View\Parsers\HTMLValue;

class HTML5Value extends HTMLValue
{
    public function setContent($content)
    {
        // Convert any errors to exceptions
        set_error_handler(
            function ($no, $str) {
                throw new Exception("HTML Parse Error: ".$str);
            },
            error_reporting()
        );

        // Use HTML5 parser to parse the HTML fragment
        try {
            $content = str_replace("\r\n", "\n", $content);
            $parser = new HTML5;
            $document = $parser->loadHTML(
                "<!DOCTYPE html>\n" .
                '<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8"></head>' .
                "<body>$content</body></html>"
            );
        } catch (Exception $e) {
            $document = false;
        }

        // Disable our error handler (restoring to previous value)
        restore_error_handler();

        // If we couldn't parse the HTML, set the error state
        if ($document) {
            $this->setDocument($document);
        } else {
            $this->setInvalid();
        }
    }
}
