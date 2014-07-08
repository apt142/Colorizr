<?php
/**
 * Color
 *
 * PHP version 5.3
 *
 * @category Color
 * @package  Colorizr
 * @author   Cris Bettis <apt142@apartment142.com>
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
 * @author   Cris Bettis <apt142@apartment142.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

class Color {
    /** @var \Silex\Application $app */
    private $app;
    /** @var \Colorizr\lib\ColorMath $colorMath */
    private $colorMath;

    /**
     * Constructor
     *
     * @param \Silex\Application      &$app      Silex Application
     * @param \Colorizr\lib\ColorMath $colorMath ColorMath Library
     *
     * @return \Colorizr\controllers\Color
     */
    public function __construct(&$app, $colorMath) {
        $this->app = $app;
        $this->colorMath = $colorMath;
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
        if ($this->validateColorString($colorString)) {
            $this->colorMath->set($colorString);
            $base       = $this->colorMath->base();
            $compliment = $this->colorMath->complementary();
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
        if ($this->validateColorString($colorString)) {
            $this->colorMath->set($colorString);
            $base = $this->colorMath->base();
            $left = $this->colorMath->hueShift(360 - $shift);
            $right = $this->colorMath->hueShift($shift);
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
        if ($this->validateColorString($colorString)) {
            $this->colorMath->set($colorString);
            $base = $this->colorMath->base();
            $left = $this->colorMath->hueShift(180 + $shift);
            $right = $this->colorMath->hueShift(180 - $shift);
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
        if ($this->validateColorString($colorString)) {
            $this->colorMath->set($colorString);
            $base   = $this->colorMath->base();
            $second = $this->colorMath->hueShift($shift);
            $third  = $this->colorMath->hueShift(180);
            $fourth = $this->colorMath->hueShift(180 + $shift);
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
     * Action to deliver an overlay
     *
     * @param string $colorString A color string
     * @param string $filter      A color string
     *
     * @return array
     */
    public function overlay($colorString, $filter) {
        $result = null;
        if ($this->validateColorString($colorString)
            && $this->validateColorString($filter)
        ) {
            $this->colorMath->set($colorString);
            $color = $this->colorMath->overlay($filter);

            $result = array(
                'result'   => $color->toHex()
            );
        }
        return $result;
    }

    /**
     * Action to deliver an multiply of two colors
     *
     * @param string $colorString A color string
     * @param string $filter      A color string
     *
     * @return array
     */
    public function multiply($colorString, $filter) {
        $result = null;
        if ($this->validateColorString($colorString)
            && $this->validateColorString($filter)
        ) {
            $this->colorMath->set($colorString);
            $color = $this->colorMath->multiply($filter);

            $result = array(
                'result'   => $color->toHex()
            );
        }
        return $result;
    }

    /**
     * Action to deliver an screen of two colors
     *
     * @param string $colorString A color string
     * @param string $filter      A color string
     *
     * @return array
     */
    public function screen($colorString, $filter) {
        $result = null;
        if ($this->validateColorString($colorString)
            && $this->validateColorString($filter)
        ) {
            $this->colorMath->set($colorString);
            $color = $this->colorMath->screen($filter);

            $result = array(
                'result'   => $color->toHex()
            );
        }
        return $result;
    }

    /**
     * Action to deliver the latest tweeted color of @everycolor
     *
     * @return array
     */
    public function everyColor() {

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
        if ($this->validateColorString($colorString)) {
            $this->colorMath->set($colorString);
            $compliment = $this->colorMath->greyscale();
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
        if ($this->validateColorString($colorString)) {
            $this->colorMath->set($colorString);
            $compliment = $this->colorMath->lighten($percent);
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
        if ($this->validateColorString($colorString)) {
            $this->colorMath->set($colorString);
            $compliment = $this->colorMath->darken($percent);
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
        if ($this->validateColorString($colorString)) {
            $this->colorMath->set($colorString);
            $compliment = $this->colorMath->saturate($percent);
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
        if ($this->validateColorString($colorString)) {
            $this->colorMath->set($colorString);
            $compliment = $this->colorMath->desaturate($percent);
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
    private function validateColorString($colorString = '') {
        return true;
    }
}
