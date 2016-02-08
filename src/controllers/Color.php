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

use \Colorizr\lib\ColorMath as ColorMath;
use Colorizr\lib\ThemeMath;

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

    const OVERLAY  = 'overlay';
    const MULTIPLY = 'multiply';
    const SCREEN   = 'screen';

    /**
     * Constructor
     *
     * @param \Silex\Application      &$app      Silex Application
     * @param \Colorizr\lib\ColorMath $colorMath ColorMath Library
     *
     * @return \Colorizr\controllers\Color
     */
    public function __construct(&$app, $colorMath)
    {
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
    public function complementary($colorString)
    {
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
    public function adjacent($colorString, $shift = 30)
    {
        $result = null;
        if ($this->validateColorString($colorString)) {
            $result = $this->colorShift($colorString, 360 - $shift, $shift);
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
    public function triad($colorString, $shift = 30)
    {
        $result = null;
        if ($this->validateColorString($colorString)) {
            $result = $this->colorShift(
                $colorString,
                180 + $shift,
                180 - $shift
            );
        }
        return $result;
    }

    /**
     * @param string $colorString Color String
     * @param int    $shiftLeft   Counter Clockwise Shift
     * @param int    $shiftRight  Clockwise Shift
     *
     * @return array
     */
    private function colorShift($colorString, $shiftLeft, $shiftRight)
    {
        $this->colorMath->set($colorString);
        $base   = $this->colorMath->base();
        $left   = $this->colorMath->hueShift($shiftLeft);
        $right  = $this->colorMath->hueShift($shiftRight);
        $result = array(
            'base'  => $base->toHex(),
            'left'  => $left->toHex(),
            'right' => $right->toHex()
        );
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
    public function quadtrad($colorString, $shift = 30)
    {
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
    public function overlay($colorString, $filter)
    {
        return $this->filterOperation($colorString, $filter, self::OVERLAY);
    }

    /**
     * Action to deliver an multiply of two colors
     *
     * @param string $colorString A color string
     * @param string $filter      A color string
     *
     * @return array
     */
    public function multiply($colorString, $filter)
    {
        return $this->filterOperation($colorString, $filter, self::MULTIPLY);
    }

    /**
     * Action to deliver an screen of two colors
     *
     * @param string $colorString A color string
     * @param string $filter      A color string
     *
     * @return array
     */
    public function screen($colorString, $filter)
    {
        return $this->filterOperation($colorString, $filter, self::SCREEN);
    }

    /**
     * Applies a filter a color
     *
     * @param string $colorString Color String
     * @param string $filter      Filter to be applied to color
     * @param string $operation   Operation Type
     *
     * @return array|null
     */
    private function filterOperation($colorString, $filter, $operation)
    {
        $result = null;
        if ($this->validateColorString($colorString)
            && $this->validateColorString($filter)
        ) {
            $this->colorMath->set($colorString);

            switch ($operation) {
                case self::OVERLAY:
                    $color = $this->colorMath->overlay($filter);
                    break;
                case self::SCREEN:
                    $color = $this->colorMath->screen($filter);
                    break;
                case self::MULTIPLY:
                    $color = $this->colorMath->multiply($filter);
                    break;
                default:
                    // #nofilter
                    $color = new \Colorizr\models\Color($colorString);
                    break;
            }

            $result = array(
                'result' => $color->toHex()
            );
        }
        return $result;
    }

    /**
     * Action to deliver the latest tweeted color of @everycolor
     *
     * @return array
     */
    public function everyColor()
    {
        // TODO: Implement this
    }

    /**
     * Action to deliver the greyscale of a color
     *
     * @param string $colorString A color string
     *
     * @return array
     */
    public function greyscale($colorString)
    {
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
     * Action to deliver the greyscale of a color
     *
     * @param string $colorString A color string
     * @param int    $normal      0-255 number to change brightness of a number
     *
     * @return array
     */
    public function normalize($colorString, $normal = 128)
    {
        $result = null;
        if ($this->validateColorString($colorString)) {
            $this->colorMath->set($colorString);
            $compliment = dechex($normal) . dechex($normal) . dechex($normal);
            $normalized = $this->colorMath->overlay($compliment);
            $result = array(
                'result' => $normalized->toHex()
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
    public function lighten($colorString, $percent)
    {
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
    public function darken($colorString, $percent)
    {
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
    public function saturate($colorString, $percent)
    {
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
    public function desaturate($colorString, $percent)
    {
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
     * Returns colors that should fit well into a theme template
     *
     * @param $colorString
     *
     * @return array
     */
    public function theme($colorString) {
        $result = null;

        if ($this->validateColorString($colorString)) {
            $themeMath = new ThemeMath();

            $palette   = $themeMath->buildActionPalette($colorString);

            $result = array(
                'primary' => $palette['primary']->toHex(),
                'info'    => $palette['info']->toHex(),
                'success' => $palette['success']->toHex(),
                'warning' => $palette['warning']->toHex(),
                'danger'  => $palette['danger']->toHex()
            );

        }

        return $result;
    }

    /**
     * Action to deliver random color
     *
     * @return array
     */
    public function random()
    {
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
    private function validateColorString($colorString = '')
    {
        // TODO: Validate please
        return true;
    }
}
