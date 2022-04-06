<?php

/**
 * This script scrapes named character references from the WHATWG
 * website.
 */

$output = dirname(__FILE__) . '/../library/HTML5/named-character-references.ser';
if (file_exists((string) $output)) {
    echo 'Output file '.realpath($output).' already exists; delete it first';
    exit;
}

$url = 'http://www.whatwg.org/specs/web-apps/current-work/multipage/named-character-references.html';
if (extension_loaded('pecl_http')) {
    $request = new HttpRequest($url);
    $request->send();
    $html = $request->getResponseBody();
} else {
    $html = file_get_contents((string) $url);
}

preg_match_all(
    '#<code title="">\s*([^<]+?)\s*</code>\s*</td>\s*<td>\s*U\+([^<]+?)\s*<#',
    (string) $html, $matches, PREG_SET_ORDER);

$table = array();
foreach ($matches as $match) {
    list(, $name, $codepoint) = $match;
    
    // Set the subtable we're working with initially to the whole table.
    $subtable =& $table;
    
    // Loop over each character to the name creating an array key for it, if it 
    // doesn't already exist
    for ($i = 0, $len = strlen($name); $i < $len; $i++) {
        if (!isset($subtable[$name[$i]])) {
            $subtable[$name[$i]] = null;
        }
        $subtable =& $subtable[$name[$i]];
    }
    
    // Set the key codepoint to the codepoint.
    $subtable['codepoint'] = hexdec((string) $codepoint);
}

file_put_contents((string) $output, serialize($table));
