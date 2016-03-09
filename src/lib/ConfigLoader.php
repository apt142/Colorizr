<?php
/**
 * ConfigLoader
 *
 * PHP version 5.3
 *
 * @category Graphite
 * @package  Core
 * @author   Cris Bettis <cris.bettis@bettercarpeople.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @link     http://g.lonefry.com
 */

namespace Colorizr\lib;


/**
 * Class ConfigLoader
 *
 * What does this class do?
 *
 * @category Colorizr\lib
 * @package  Core
 * @author   Cris Bettis <cris.bettis@bettercarpeople.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @link     http://g.lonefry.com
 */
class ConfigLoader {
    public static function loadConfig($config)
    {
        $vars = null;

        $filename = __DIR__ . '/../config/' . $config . '.php';
        if (file_exists($filename)) {
            $vars = include $filename;
        }

        return $vars;
    }
}
