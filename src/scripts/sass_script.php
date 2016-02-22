<?php

if (!class_exists('Sass')) {
    die("ERROR - libSass binding extension is not installed. See https://github.com/sensational/sassphp\n");
}

$content     = '';
$includePath = '';

switch ($argc) {
    case 3:
        $includePath = $argv[1];
        $content     = $argv[2];
        break;

    case 2;
        $content     = $argv[1];
        break;

    default:
        break;
}

if (empty($content)) {
    die("ERROR - Nothing to parse\n");
} else {
    $sass = new Sass();
    if (!empty($includePath)) {
        $paths = explode(',', $includePath);
        foreach ($paths as $path) {
            $sass->setIncludePath($path);
        }
    }
    die($sass->compile($content));
}
