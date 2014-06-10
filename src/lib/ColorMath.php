<?php
/**
 * ColorMath
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
 * Class ColorMath
 *
 * Computes operations on a color.
 *
 * @category Colorizr\lib
 * @package  Core
 * @author   Cris Bettis <cris.bettis@bettercarpeople.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @link     http://g.lonefry.com
 */
class ColorMath {

    private $color = null;

    public function __construct($colorString = null) {
         $this->set($colorString);

    }

     /**
      * Creates a Color object out of a hex string
      *
      * @param string $colorString Hex String for a color.  eg FF0000, #ff0000, f00
      *
      * @return \Colorizr\models\Color
      */
    public function set($colorString) {
         $this->color = $this->create($colorString);
         return $this->color;
    }

    /**
     * Creates a new color object
     *
     * @param string|null $colorString Color String
     *
     * @return \Colorizr\models\Color
     */
    public function create($colorString) {
        $colorString = is_string($colorString) ? $colorString : '';
        $colorString = strtolower($colorString);
        $colorString = preg_replace("/[^0-9a-f]/", '', $colorString);
        $red   = 0;
        $green = 0;
        $blue  = 0;
        if (strlen($colorString) == 6) {
            $colorVal = hexdec($colorString);
            // Bitwise calculation
            $red   = 0xFF & ($colorVal >> 0x10);
            $green = 0xFF & ($colorVal >> 0x8);
            $blue  = 0xFF & $colorVal;
        } elseif (strlen($colorString) == 3) { //if shorthand notation, need some string manipulations
            $red   = hexdec(str_repeat(substr($colorString, 0, 1), 2));
            $green = hexdec(str_repeat(substr($colorString, 1, 1), 2));
            $blue  = hexdec(str_repeat(substr($colorString, 2, 1), 2));
        }
        return new \Colorizr\models\Color($red, $green, $blue);
    }


    /**
     * Darkens a color by a certain percentage
     *
     * @param float $percent Percentage to reduce it by.
     *
     * @return \Colorizr\models\Color
     */
    public function darken($percent) {
        $percent    = $this->_sanitizePercentage($percent);
        $mulitplier = (100 - $percent) / 100.0;
        return $this->_multiplyColor($mulitplier);
    }

    /**
     * Darkens a color by a certain percentage
     *
     * @param float $percent Percentage to reduce it by.
     *
     * @return \Colorizr\models\Color
     */
    public function lighten($percent) {
        $percent    = $this->_sanitizePercentage($percent);
        $multiplier = (100 + $percent) / 100.0;
        return $this->_multiplyColor($multiplier);
    }


    /**
     * Desaturates a Color
     *
     * @param string|null $colorString Color String
     *
     * @return \Colorizr\models\Color
     */
    public function desaturate($colorString = null) {
        if ($colorString == null) {
            $color = $this->color;
        } else {
            $color = $this->create($colorString);
        }
        $desat = (int) round($color->red * 0.299
            + $color->green * 0.587
            + $color->blue * 0.114);
        return new \Colorizr\models\Color($desat, $desat, $desat);
    }

    /**
     * Saturates a color
     *
     * @param int $percent
     *
     * @return \Colorizr\models\Color
     */
    public function saturate($percent) {
        $percent = $this->_sanitizePercentage($percent) / 100.0;
        $color2 = $this->desaturate();
        $red   = $percent * $this->color->red + (1.0 - $percent) * $color2->red;
        $green = $percent * $this->color->green + (1.0 - $percent) * $color2->green;
        $blue  = $percent * $this->color->blue + (1.0 - $percent) * $color2->blue;
        return new \Colorizr\models\Color($red, $green, $blue);
    }

    /**
     * Checks percent and returns a valid value
     *
     * @param mixed $percent Percent to
     *
     * @return int
     */
    private function _sanitizePercentage($percent) {
        // Sanitize the bad inputs
        if (!is_numeric($percent)) {
            $percent = 0;
        }
        return $percent;
    }

    /**
     * Multiplies a color
     *
     * @param float $multiplier Multiplier
     *
     * @return \Colorizr\models\Color
     */
    private function _multiplyColor($multiplier) {
        $modColor = $this->color;
        $red      = (int) round($modColor->red * $multiplier);
        $green    = (int) round($modColor->green * $multiplier);
        $blue     = (int) round($modColor->blue * $multiplier);

        $modColor->red   = ($red > 255) ? 255 : $red;
        $modColor->green = ($green > 255) ? 255 : $green;
        $modColor->blue  = ($blue > 255) ? 255 : $blue;
        return $modColor;
    }
}
