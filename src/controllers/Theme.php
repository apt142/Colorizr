<?php
/**
 * Theme
 *
 * PHP version 5.3
 *
 * @category Theme
 * @package  Themeizr
 * @author   Cris Bettis <apt142@apartment142.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

namespace Colorizr\controllers;

/**
 * Class Theme
 *
 * Theme Parent Class
 *
 * @category Theme
 * @package  Themeizr
 * @author   Cris Bettis <apt142@apartment142.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

class Theme {
    /** @var \Silex\Application $_app */
    private $app;

    /**
     * Constructor
     *
     * @param \Silex\Application &$app Silex Application
     *
     * @return \Colorizr\controllers\Theme
     */
    public function __construct(&$app)
    {
        $this->app = $app;
    }

    /**
     * Action to deliver complementary Theme
     *
     * @param string $colorString Optional Color String
     *
     * @return array
     */
    public function themeCues($colorString = null)
    {
        $result = array(
            'color' => $colorString
        );

        return $result;
    }
}
