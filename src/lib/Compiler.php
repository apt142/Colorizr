<?php
/**
 * Compiler
 *
 * PHP version 5.3
 *
 * @category Graphite
 * @package  Core
 * @author   Cris Bettis <cris.bettis@bettercarpeople.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

namespace Colorizr\lib;

/**
 * Class Compiler
 *
 * Wrapper for some sass compiling libraries that consolidates the interface
 * and picks the fastest method to compile with.
 *
 * @category Colorizr\lib
 * @package  Core
 * @author   Cris Bettis <cris.bettis@bettercarpeople.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
class Compiler {

    private $importPaths = array();

    /**
     * Set the import path(s)
     *
     * @param string|array $path Import paths
     *
     * @return void
     */
    public function setImportPaths($path)
    {
        $this->importPaths = (array) $path;
    }

    /**
     * Compiles the source using the fastest library available
     *
     * @param string $source Raw Sass code
     *
     * @return null|string
     */
    public function compile($source)
    {
        $results = null;

        $dreamhostPath = __DIR__ . '/../../../local/bin/php';

        if (class_exists('\Sass')) {
            // Use the Sass.so extension because it is super quick
            $sass = new \Sass();
            foreach ($this->importPaths as $path) {
                $sass->setIncludePath($path);
            }
            $results = $sass->compile($source);
        } else if (file_exists($dreamhostPath)) {
            $output = array();

            $importPathPrefix = __DIR__ . '/../../public/';
            $importPaths = $importPathPrefix
                . implode(',' . $importPathPrefix, $this->importPaths);
            $cmd = $dreamhostPath . ' '
                . __DIR__ . '/../scripts/sass_script.php'
                . ' ' . escapeshellarg($importPaths)
                . ' ' . escapeshellarg($source);

            exec(
                $cmd,
                $output
            );
            $results = implode("\n", $output);
        } else {
            // Use the Leaf/SassCompiler library
            $scss = new \Leafo\ScssPhp\Compiler();
            $scss->setImportPaths($this->importPaths);

            $results = $scss->compile($source);
        }

        return $results;
    }
}
