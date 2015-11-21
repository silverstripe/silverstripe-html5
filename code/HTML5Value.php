<?php

class SS_HTML5Value extends SS_HTMLValue
{
    public function setContent($content)
    {
        require_once(HTML5LIB_PATH.'/HTML5/Parser.php');

        // Convert any errors to exceptions
        set_error_handler(
            function ($no, $str) {
                throw new Exception("HTML Parse Error: ".$str);
            },
            error_reporting()
        );

        // Use HTML5lib to parse the HTML fragment
        try {
            $document = HTML5_Parser::parse(
                '<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8"></head>'.
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
