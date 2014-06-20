<?php
/**
 * Color
 *
 * PHP version 5.3
 *
 * @category Graphite
 * @package  Core
 * @author   Cris Bettis <cris.bettis@bettercarpeople.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @link     http://g.lonefry.com
 */

namespace Colorizr\models;


/**
 * Class Color
 *
 * What does this class do?
 *
 * @category Colorizr\models
 * @package  Core
 * @author   Cris Bettis <cris.bettis@bettercarpeople.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @link     http://g.lonefry.com
 */
class Color {
    public $red   = 0;
    public $green = 0;
    public $blue  = 0;
    public $alpha = 0;

    /**
     * Constructor
     *
     * @param int $red   Red Value
     * @param int $green Green Value
     * @param int $blue  Blue Value
     * @param int $alpha Alpha Value
     *
     * @return \Colorizr\models\Color
     */
    public function __construct($red, $green, $blue, $alpha = null) {
        $this->red   = (int) round($red);
        $this->green = (int) round($green);
        $this->blue  = (int) round($blue);
        $this->alpha = $alpha;
    }

    /**
     * Converts value to hex format
     *
     * @return string
     */
    public function toHex() {
        return $this->_TwoCharHex($this->red)
            . $this->_TwoCharHex($this->green)
            . $this->_TwoCharHex($this->blue);
    }

    /**
     * Converts int to 2 digit hex
     *
     * @param int $int Integer to convert
     *
     * @return string
     */
    private function _TwoCharHex($int) {
        $int = dechex((int) round($int));
        $int = strlen($int) == 1
            ? '0' . $int
            : $int;
        return $int;
    }

    /**
     * Converts value to rgb(#,#,#) format
     *
     * @return string
     */
    public function toRGB() {
        return 'rgb(' . (int) $this->red . ',' . (int) $this->green. ','
            . (int) $this->blue . ')';
    }

    /**
     * Converts value to rgba(#,#,#,#) format
     *
     * @return string
     */
    public function toRGBA() {
        return 'rgba(' . (int) $this->red . ',' . (int) $this->green. ','
            . (int) $this->blue . ',' . $this->alpha . ')';
    }

    /**
     * Converts to HSL format
     *
     * @return object
     */
    public function toHSL() {
        $red = $this->red / 255.0;
        $green = $this->green / 255.0;
        $blue = $this->blue / 255.0;

        $min = min($red, $green, $blue);
        $max = max($red, $green, $blue);
        $delta = $max - $min;

        $l = ($max + $min) / 2;

        if ($delta == 0) {
            $h = 0;
            $s = 0;
        } else {
            if ($l < 0.5) {
                $s = $delta / ($max + $min);
            } else {
                $s = $delta / (2 - $max - $min);
            }

            $dRed = ((($max - $red) / 6) + ($delta / 2)) / $delta;
            $dGreen = ((($max - $green) / 6) + ($delta / 2)) / $delta;
            $dBlue = ((($max - $blue) / 6) + ($delta / 2)) / $delta;

            if ($red == $max) {
                $h = $dBlue - $dGreen;
            } elseif ($green == $max) {
                $h = (1 / 3) + $dRed - $dBlue;
            } elseif ($blue == $max) {
                $h = (2 / 3) + $dGreen - $dRed;
            }

            if ($h < 0) {
                $h += 1;
            }

            if ($h > 1) {
                $h -= 1;
            }
        }

        return (object) array(
            'h' => $h * 360,
            's' => $s * 100,
            'l' => $l * 100
        );
    }

    /**
     * Set RGB values for an HSL entry
     *
     * @param float $h Hue
     * @param float $s Saturation
     * @param float $l Luminosity
     *
     * @return $this
     */
    public function fromHSL($h, $s, $l) {
        $h = ($h % 360) / 360.0;
        $s = $s / 100.0;
        $l = $l / 100.0;

        if ($s == 0) {
            $this->red   = $l * 255;
            $this->green = $l * 255;
            $this->blue  = $l * 255;
        } else {
            if ($l < 0.5) {
                $factor2 = $l * (1 + $s);
            } else {
                $factor2 = ($l + $s) - ($s * $l);
            }

            $factor1 = 2 * $l - $factor2;
            $this->red   = 255 * $this->hueToRGB($factor1, $factor2, $h + (1 / 3));
            $this->green = 255 * $this->hueToRGB($factor1, $factor2, $h);
            $this->blue  = 255 * $this->hueToRGB($factor1, $factor2, $h - (1 / 3));
        };
        return $this;
    }

    /**
     * Converts hsl to a RGB channel
     *
     * @param float $factor1 Factor 1
     * @param float $factor2 Factor 1
     * @param float $hue     Hue
     *
     * @return mixed
     */
    private function hueToRGB($factor1, $factor2, $hue) {
        if ($hue < 0) {
            $hue += 1;
        }

        if ($hue > 1) {
            $hue -= 1;
        }

        $result = $factor1;
        if ((6 * $hue) < 1) {
            $result = $factor1 + ($factor2 - $factor1) * 6 * $hue;
        } elseif ((2 * $hue) < 1) {
            $result = $factor2;
        } elseif ((3 * $hue) < 2) {
            $result = $factor1 + ($factor2 - $factor1) * ((2 / 3 - $hue) * 6);
        }
        return $result;
    }
}
