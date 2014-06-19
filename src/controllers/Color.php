<?php
/**
 * Color
 *
 * PHP version 5.3
 *
 * @category Color
 * @package  Colorizr
 * @author   Cris Bettis <cris.bettis@bettercarpeople.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

namespace Colorizr\controllers;

/**
 * Class Color
 *
 * Color Parent Class
 *
 * @category Color
 * @package  Colorizr
 * @author   Cris Bettis <cris.bettis@bettercarpeople.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

class Color {
    /** @var \Silex\Application $_app */
    private $_app;
    /** @var \Colorizr\lib\ColorMath $_colorMath */
    private $_colorMath;

    /**
     * Constructor
     *
     * @param \Silex\Application      &$app      Silex Application
     * @param \Colorizr\lib\ColorMath $colorMath ColorMath Library
     *
     * @return void
     */
    public function init(&$app, $colorMath) {
        $this->_app = $app;
        $this->_colorMath = $colorMath;
    }

    /**
     * Action to deliver complimentary color
     *
     * @param string $colorString A color string
     *
     * @return array
     */
    public function complimentary($colorString) {
        $result = null;
        if ($this->_validateColorString($colorString)) {
            $this->_colorMath->set($colorString);
            $compliment = $this->_colorMath->complimentary();
            $result = array(
                'result' => $compliment->toRGB()
            );
        }
        return $result;
    }

    /**
     * Validates if the string is able to be parsed
     *
     * @param String $colorString Color String
     *
     * @return bool
     */
    private function _validateColorString($colorString) {
        return true;
    }
}
