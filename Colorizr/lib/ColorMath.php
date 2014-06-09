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
         $this->color = new \Colorizr\models\Color($red, $green, $blue);
         return $this->color;
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
        if ($percent < 0) {
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
        $red      = $modColor->red * $multiplier;
        $green    = $modColor->green * $multiplier;
        $blue     = $modColor->blue * $multiplier;

        $modColor->red   = ($red > 255) ? 255 : $red;
        $modColor->green = ($green > 255) ? 255 : $green;
        $modColor->blue  = ($blue > 255) ? 255 : $blue;
        return $modColor;
    }
}
