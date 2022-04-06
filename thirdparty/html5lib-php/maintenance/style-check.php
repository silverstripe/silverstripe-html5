<?php

/**
 * This script enforces several style constraints:
 *      - Converts tabs to spaces
 *      - Adds vimlines to files
 */

function style_check($dir) {
    foreach (glob("$dir/*") as $node) {
        if (is_file((string) $node)) style_correct($node);
        elseif (is_dir((string) $node)) style_check("$node");
    }
}

function style_correct($file) {
    $exclude_prefixes = array(
        './tests/HTML5/testdata/',
    );
    $exclude_extensions = array();
    foreach ($exclude_prefixes as $p) {
        if (strncmp((string) $p, (string) $file, strlen((string) $p)) === 0) return;
    }
    foreach ($exclude_extensions as $e) {
        if (strlen((string) $file) > strlen((string) $e) && substr((string) $file, -strlen((string) $e)) === $e) {
            return;
        }
    }
    $orig = $contents = file_get_contents((string) $file);
    
    // vimline
    $contents = style_add_vimline($file, $contents);
    
    // tab2space
    $contents = str_replace("\t", '    ', $contents ?: '');
    
    if ($orig !== $contents) {
        echo "$file\n";
        file_put_contents((string) $file, $contents);
    }
}

function style_add_vimline($file, $contents) {
    $vimline = 'et sw=4 sts=4';
    $ext = strrchr((string) $file, '.');
    if (strpos((string) $ext, '/') !== false) $ext = '.txt';
    $no_nl = false;
    switch ($ext) {
        case '.php':
            $line = '// %s';
            break;
        case '.txt':
            $line = '    %s';
            break;
        default:
            throw new Exception('Unrecognized extension');
    }
    $regex = '~' . str_replace('%s', 'vim: .+', preg_quote((string) $line, '~')) .  '~m';
    $contents = preg_replace($regex ?: '', '', $contents ?: '');

    $contents = rtrim((string) $contents);

    if (strpos((string) $contents, "\r\n") !== false) $nl = "\r\n";
    elseif (strpos((string) $contents, "\n") !== false) $nl = "\n";
    elseif (strpos((string) $contents, "\r") !== false) $nl = "\r";
    else $nl = PHP_EOL;

    if (!$no_nl) $contents .= $nl;
    $contents .= $nl . str_replace('%s', 'vim: ' . $vimline, $line ?: '') . $nl;
    return $contents;
}

chdir(dirname(__FILE__) . '/..');
style_check('.');

// vim: et sw=4 sts=4
