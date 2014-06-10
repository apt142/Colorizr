<?php
/**
 * Controller
 *
 * PHP version 5.3
 *
 * @category Controller
 * @package  Colorizr
 * @author   Cris Bettis <cris.bettis@bettercarpeople.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

namespace Colorizr\controllers;

/**
 * Class Controller
 *
 * Controller Parent Class
 *
 * @category Controller
 * @package  Colorizr
 * @author   Cris Bettis <cris.bettis@bettercarpeople.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

class Controller {
    private $_app;

    public function init($app) {
        $this->_app = $app;
    }

}
