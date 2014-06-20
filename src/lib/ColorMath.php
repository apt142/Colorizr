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

use \Colorizr\models\Color;

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

    /** @var \Colorizr\models\Color $color */
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
     * Returns base color
     *
     * @return \Colorizr\models\Color
     */
    public function base() {
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
        return new Color($red, $green, $blue);
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
    public function greyscale($colorString = null) {
        if ($colorString == null) {
            $color = $this->color;
        } else {
            $color = $this->create($colorString);
        }
        $desat = (int) round($color->red * 0.299
            + $color->green * 0.587
            + $color->blue * 0.114);
        /* $desat = (int) round($color->red
            + $color->green
            + $color->blue);
        */
        return new Color($desat, $desat, $desat);
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
        $color2 = $this->greyscale();
        $red   = $percent * $this->color->red + (1.0 - $percent) * $color2->red;
        $green = $percent * $this->color->green + (1.0 - $percent) * $color2->green;
        $blue  = $percent * $this->color->blue + (1.0 - $percent) * $color2->blue;
        return new Color($red, $green, $blue);
    }

    /**
     * Desaturates a color
     *
     * @param int $percent
     *
     * @return \Colorizr\models\Color
     */
    public function desaturate($percent) {
        $percent = $this->_sanitizePercentage($percent) / 100.0 * -1;
        $color2 = $this->greyscale();
        $red   = $percent * $this->color->red + (1.0 - $percent) * $color2->red;
        $green = $percent * $this->color->green + (1.0 - $percent) * $color2->green;
        $blue  = $percent * $this->color->blue + (1.0 - $percent) * $color2->blue;
        return new Color($red, $green, $blue);
    }

    /**
     * Multiplies two colors together
     *
     * @param String $colorString
     *
     * @return \Colorizr\models\Color
     */
    public function multiply($colorString) {
        $color2 = $this->create($colorString);
        return $this->_multiplyColors($this->color, $color2);
    }

    /**
     * Multiplies two colors together
     *
     * @param String $colorString
     *
     * @return \Colorizr\models\Color
     */
    public function screen($colorString) {
        $color2 = $this->create($colorString);
        $red    = $this->color->red;
        $green  = $this->color->green;
        $blue   = $this->color->blue;

        $red2   = $color2->red;
        $green2 = $color2->green;
        $blue2  = $color2->blue;

        return new \Colorizr\models\Color(
            $this->_screenChannel($red, $red2),
            $this->_screenChannel($green, $green2),
            $this->_screenChannel($blue, $blue2)
        );
    }

    /**
     * Overlays a color with a modifying color
     *
     * @param String $colorString Color String
     *
     * @return \Colorizr\models\Color
     */
    public function overlay($colorString) {
        $modifier = $this->create($colorString);
        $product = new \Colorizr\models\Color(0, 0, 0);

        foreach (array('red', 'green', 'blue') as $channel) {
            if ($this->color->$channel >= 128) {
                $product->$channel = $this->_screenChannel(
                    $this->color->$channel,
                    $modifier->$channel
                );
            } else {
                $product->$channel = $this->_multiplyChannel(
                    $this->color->$channel * 2,
                    $modifier->$channel
                );

            }
        }
        return $product;
    }

    /**
     * Creates a complementary color
     *
     * @return \Colorizr\models\Color
     */
    public function complementary() {
        return $this->hueShift(180);
    }

    /**
     * Shifts the hue by a degree
     *
     * @param int $degree Degree to shift 0 - 360
     *
     * @return \Colorizr\models\Color
     */
    public function hueShift($degree) {
        $color = $this->cloneColor($this->color);
        $hsl = $color->toHSL();
        $h = ($hsl->h + $degree);
        return $color->fromHSL($h, $hsl->s, $hsl->l);
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
     * Multiplies each channel in a color by a base amount
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

    /**
     * Screen Calculation
     *
     * @param int $base Base channel color
     * @param int $mod  Color channel modifying it
     *
     * @return int
     */
    private function _screenChannel($base, $mod) {
        return $base + $mod - ($base * $mod) / 255;
    }

    /**
     * Multiply Calculation
     *
     * @param int $base Base channel color
     * @param int $mod  Color channel modifying it
     *
     * @return int
     */
    private function _multiplyChannel($base, $mod) {
        return ($base * $mod) / 255;
    }

    /**
     * Multiply two colors together
     *
     * @param Color $base      Base Color
     * @param Color $modifier Secondary Color
     *
     * @return \Colorizr\models\Color
     */
    private function _multiplyColors($base, $modifier) {
        return new \Colorizr\models\Color(
            $this->_multiplyChannel($base->red, $modifier->red),
            $this->_multiplyChannel($base->green, $modifier->green),
            $this->_multiplyChannel($base->blue, $modifier->blue)
        );
    }

    /**
     * Clones a color
     *
     * @param \Colorizr\models\Color $color Color
     *
     * @return \Colorizr\models\Color
     */
    public function cloneColor($color) {
         return new \Colorizr\models\Color(
            $color->red,
            $color->green,
            $color->blue
        );
    }
}
