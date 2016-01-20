<?php
/**
 * Color
 *
 * PHP version 5.3
 *
 * @category Colorizr
 * @package default
 * @author   Cris Bettis <apt142@apartment142.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @link     http://apartment142.com
 */

namespace Colorizr\models;


/**
 * Class Color
 *
 * What does this class do?
 *
 * @category Colorizr\models
 * @package default
 * @author   Cris Bettis <apt142@apartment142.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @link     http://apartment142.com
 */
class Color {
    public $red   = 0;
    public $green = 0;
    public $blue  = 0;
    public $alpha = 0;

    /**
     * Constructor
     *
     * @param int|string $red   Red Value
     * @param int|null   $green Green Value
     * @param int|null   $blue  Blue Value
     * @param int|null   $alpha Alpha Value (Not Used Yet)
     *
     * @return \Colorizr\models\Color
     */
    public function __construct(
        $red,
        $green = null,
        $blue = null,
        $alpha = null
    ) {
        if (is_string($red)) {
            $this->fromString($red);
        } else {
            $this->red   = (int) round($red);
            $this->green = (int) round($green);
            $this->blue  = (int) round($blue);
            $this->alpha = $alpha;
        }

    }

    /**
     * Converts value to hex format
     *
     * @return string
     */
    public function toHex()
    {
        return $this->twoCharHex($this->red)
            . $this->twoCharHex($this->green)
            . $this->twoCharHex($this->blue);
    }

    /**
     * Converts int to 2 digit hex
     *
     * @param int $int Integer to convert
     *
     * @return string
     */
    private function twoCharHex($int)
    {
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
    public function toRGB()
    {
        return 'rgb(' . (int) $this->red . ',' . (int) $this->green. ','
            . (int) $this->blue . ')';
    }

    /**
     * Converts value to rgba(#,#,#,#) format
     *
     * @return string
     */
    public function toRGBA()
    {
        return 'rgba(' . (int) $this->red . ',' . (int) $this->green. ','
            . (int) $this->blue . ',' . $this->alpha . ')';
    }

    /**
     * Converts to HSL format
     *
     * @return object
     */
    public function toHSL()
    {
        $red = $this->red / 255.0;
        $green = $this->green / 255.0;
        $blue = $this->blue / 255.0;

        $min = min($red, $green, $blue);
        $max = max($red, $green, $blue);
        $delta = $max - $min;

        $luminosity = ($max + $min) / 2;

        if ($delta == 0) {
            $hue = 0;
            $saturation = 0;
        } else {
            if ($luminosity < 0.5) {
                $saturation = $delta / ($max + $min);
            } else {
                $saturation = $delta / (2 - $max - $min);
            }

            $dRed = ((($max - $red) / 6) + ($delta / 2)) / $delta;
            $dGreen = ((($max - $green) / 6) + ($delta / 2)) / $delta;
            $dBlue = ((($max - $blue) / 6) + ($delta / 2)) / $delta;

            if ($red == $max) {
                $hue = $dBlue - $dGreen;
            } elseif ($green == $max) {
                $hue = (1 / 3) + $dRed - $dBlue;
            } elseif ($blue == $max) {
                $hue = (2 / 3) + $dGreen - $dRed;
            }

            if ($hue < 0) {
                $hue += 1;
            }

            if ($hue > 1) {
                $hue -= 1;
            }
        }

        return (object) array(
            'h' => $hue * 360,
            's' => $saturation * 100,
            'l' => $luminosity * 100
        );
    }

    /**
     * Instantiates RGB values from string
     *
     * @param string $colorString
     *
     * @return void
     */
    private function fromString($colorString)
    {
        $colorString = is_string($colorString) ? $colorString : '';
        $colorString = strtolower($colorString);
        $colorString = preg_replace("/[^0-9a-f]/", '', $colorString);
        if (strlen($colorString) == 6) {
            // 6 Character String
            $colorVal = hexdec($colorString);
            // Bitwise calculation
            $this->red   = 0xFF & ($colorVal >> 0x10);
            $this->green = 0xFF & ($colorVal >> 0x8);
            $this->blue  = 0xFF & $colorVal;
        } elseif (strlen($colorString) == 3) {
            //if shorthand notation, need some string manipulations
            $this->red   = hexdec(str_repeat(substr($colorString, 0, 1), 2));
            $this->green = hexdec(str_repeat(substr($colorString, 1, 1), 2));
            $this->blue  = hexdec(str_repeat(substr($colorString, 2, 1), 2));
        } else {
            // I don't understand this string so we go black!
            $this->red   = 0;
            $this->green = 0;
            $this->blue  = 0;
        }
    }

    /**
     * Set RGB values for an HSL entry
     *
     * @param float $hue        Hue
     * @param float $saturation Saturation
     * @param float $luminosity Luminosity
     *
     * @return $this
     */
    public function fromHSL($hue, $saturation, $luminosity)
    {
        $hue = ($hue % 360) / 360.0;
        $saturation = $saturation / 100.0;
        $luminosity = $luminosity / 100.0;

        if ($saturation == 0) {
            $this->red   = $luminosity * 255;
            $this->green = $luminosity * 255;
            $this->blue  = $luminosity * 255;
        } else {
            if ($luminosity < 0.5) {
                $factor2 = $luminosity * (1 + $saturation);
            } else {
                $factor2 = ($luminosity + $saturation) - ($saturation * $luminosity);
            }

            $factor1 = 2 * $luminosity - $factor2;
            $this->red   = 255 * $this->hueToRGB($factor1, $factor2, $hue + (1 / 3));
            $this->green = 255 * $this->hueToRGB($factor1, $factor2, $hue);
            $this->blue  = 255 * $this->hueToRGB($factor1, $factor2, $hue - (1 / 3));
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
    private function hueToRGB($factor1, $factor2, $hue)
    {
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
