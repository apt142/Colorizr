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
     * @return \Colorizr\controllers\Color
     */
    public function __construct(&$app, $colorMath) {
        $this->_app = $app;
        $this->_colorMath = $colorMath;
    }

    /**
     * Action to deliver complementary color
     *
     * @param string $colorString A color string
     *
     * @return array
     */
    public function complementary($colorString) {
        $result = null;
        if ($this->_validateColorString($colorString)) {
            $this->_colorMath->set($colorString);
            $base       = $this->_colorMath->base();
            $compliment = $this->_colorMath->complementary();
            $result = array(
                'base'       => $base->toHex(),
                'compliment' => $compliment->toHex()
            );
        }
        return $result;
    }

    /**
     * Action to deliver adjacent colors
     *
     * @param string $colorString A color string
     * @param int    $shift       Degree to shift values
     *
     * @return array
     */
    public function adjacent($colorString, $shift = 30) {
        $result = null;
        if ($this->_validateColorString($colorString)) {
            $this->_colorMath->set($colorString);
            $base = $this->_colorMath->base();
            $left = $this->_colorMath->hueShift(360 - $shift);
            $right = $this->_colorMath->hueShift($shift);
            $result = array(
                'base'  => $base->toHex(),
                'left'  => $left->toHex(),
                'right' => $right->toHex()
            );
        }
        return $result;
    }

    /**
     * Action to deliver triad colors
     *
     * @param string $colorString A color string
     * @param int    $shift       Degree to shift values
     *
     * @return array
     */
    public function triad($colorString,  $shift = 30) {
        $result = null;
        if ($this->_validateColorString($colorString)) {
            $this->_colorMath->set($colorString);
            $base = $this->_colorMath->base();
            $left = $this->_colorMath->hueShift(180 + $shift);
            $right = $this->_colorMath->hueShift(180 - $shift);
            $result = array(
                'base'  => $base->toHex(),
                'left'  => $left->toHex(),
                'right' => $right->toHex()
            );
        }
        return $result;
    }

    /**
     * Action to deliver quadtrad colors
     *
     * @param string $colorString A color string
     * @param int    $shift       Degree to shift values
     *
     * @return array
     */
    public function quadtrad($colorString, $shift = 30) {
        $result = null;
        if ($this->_validateColorString($colorString)) {
            $this->_colorMath->set($colorString);
            $base   = $this->_colorMath->base();
            $second = $this->_colorMath->hueShift($shift);
            $third  = $this->_colorMath->hueShift(180);
            $fourth = $this->_colorMath->hueShift(180 + $shift);
            $result = array(
                'base'   => $base->toHex(),
                'second' => $second->toHex(),
                'third'  => $third->toHex(),
                'fourth' => $fourth->toHex()
            );
        }
        return $result;
    }

    /**
     * Action to deliver the greyscale of a color
     *
     * @param string $colorString A color string
     *
     * @return array
     */
    public function greyscale($colorString) {
        $result = null;
        if ($this->_validateColorString($colorString)) {
            $this->_colorMath->set($colorString);
            $compliment = $this->_colorMath->greyscale();
            $result = array(
                'result' => $compliment->toHex()
            );
        }
        return $result;
    }

    /**
     * Returns a lightened color
     *
     * @param string $colorString A color string
     * @param int    $percent     Percent to lighten
     *
     * @return array
     */
    public function lighten($colorString, $percent) {
        $result = null;
        if ($this->_validateColorString($colorString)) {
            $this->_colorMath->set($colorString);
            $compliment = $this->_colorMath->lighten($percent);
            $result = array(
                'result' => $compliment->toHex()
            );
        }
        return $result;
    }

    /**
     * Returns a darkened color
     *
     * @param string $colorString A color string
     * @param int    $percent     Percent to lighten
     *
     * @return array
     */
    public function darken($colorString, $percent) {
        $result = null;
        if ($this->_validateColorString($colorString)) {
            $this->_colorMath->set($colorString);
            $compliment = $this->_colorMath->darken($percent);
            $result = array(
                'result' => $compliment->toHex()
            );
        }
        return $result;
    }

    /**
     * Returns a saturated color
     *
     * @param string $colorString A color string
     * @param int    $percent     Percent to lighten
     *
     * @return array
     */
    public function saturate($colorString, $percent) {
        $result = null;
        if ($this->_validateColorString($colorString)) {
            $this->_colorMath->set($colorString);
            $compliment = $this->_colorMath->saturate($percent);
            $result = array(
                'result' => $compliment->toHex()
            );
        }
        return $result;
    }

    /**
     * Returns a desaturated color
     *
     * @param string $colorString A color string
     * @param int    $percent     Percent to lighten
     *
     * @return array
     */
    public function desaturate($colorString, $percent) {
        $result = null;
        if ($this->_validateColorString($colorString)) {
            $this->_colorMath->set($colorString);
            $compliment = $this->_colorMath->desaturate($percent);
            $result = array(
                'result' => $compliment->toHex()
            );
        }
        return $result;
    }

    /**
     * Action to deliver random color
     *
     * @return array
     */
    public function random() {
        $color = new \Colorizr\models\Color(
            rand(0, 255),
            rand(0, 255),
            rand(0, 255)
        );
        return array(
            'result' => $color->toHex()
        );
    }

    /**
     * Validates if the string is able to be parsed
     *
     * @param String $colorString Color String
     *
     * @return bool
     */
    private function _validateColorString($colorString = '') {
        return true;
    }
}
