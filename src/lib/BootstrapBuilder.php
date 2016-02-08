<?php
/**
 * BootstrapBuilder
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

use Silex\Application;
use Symfony\Component\HttpFoundation\Request as Request;

/**
 * Class BootstrapBuilder
 *
 * What does this class do?
 *
 * @category Colorizr\lib
 * @package  Core
 * @author   Cris Bettis <cris.bettis@bettercarpeople.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @link     http://g.lonefry.com
 */
class BootstrapBuilder extends FrameworkBuilder {

    protected $importPath  = 'components/bootstrap-sass/assets/stylesheets';
    protected $savePath    = 'stylesheets/bootstrap/';
    protected $fileName    = 'bootstrap.css';
    protected $varTemplate = 'bootstrap-var-file.twig';

    protected $defaultFont = '"Helvetica Neue", Helvetica, Arial, sans-serif';
}
