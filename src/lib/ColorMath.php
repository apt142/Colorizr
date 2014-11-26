<?php
/**
 * ColorMath
 *
 * PHP version 5.3
 *
 * @category Colorizr
 * @package default
 * @author   Cris Bettis <apt142@apartment142.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @link     http://apartment142.com
 */

namespace Colorizr\lib;

use \Colorizr\models\Color;

/**
 * Class ColorMath
 *
 * Computes operations on a color.
 *
 * @category Colorizr\lib
 * @package default
 * @author   Cris Bettis <apt142@apartment142.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @link     http://apartment142.com
 */
class ColorMath {

    /** @var \Colorizr\models\Color $color */
    private $color = null;

    /**
     * Constructor
     *
     * @param string|null $colorString Color String
     *
     * @return \Colorizr\lib\ColorMath
     */
    public function __construct($colorString = null)
    {
         $this->set($colorString);

    }

     /**
      * Creates a Color object out of a hex string
      *
      * @param string $colorString Hex String for a color.  eg FF0000, #ff0000, f00
      *
      * @return \Colorizr\models\Color
      */
    public function set($colorString)
    {
         $this->color = new Color($colorString);
         return $this->color;
    }

    /**
     * Returns base color
     *
     * @return \Colorizr\models\Color
     */
    public function base()
    {
        return $this->color;
    }

    /**
     * Darkens a color by a certain percentage
     *
     * @param float $percent Percentage to reduce it by.
     *
     * @return \Colorizr\models\Color
     */
    public function darken($percent)
    {
        $percent    = $this->sanitizePercentage($percent);
        $mulitplier = (100 - $percent) / 100.0;
        return $this->multiplyColor($mulitplier);
    }

    /**
     * Darkens a color by a certain percentage
     *
     * @param float $percent Percentage to reduce it by.
     *
     * @return \Colorizr\models\Color
     */
    public function lighten($percent)
    {
        $percent    = $this->sanitizePercentage($percent);
        $multiplier = (100 + $percent) / 100.0;
        return $this->multiplyColor($multiplier);
    }


    /**
     * Desaturates a Color
     *
     * @param string|null $colorString Color String
     *
     * @return \Colorizr\models\Color
     */
    public function greyscale($colorString = null)
    {
        if ($colorString == null) {
            $color = $this->color;
        } else {
            $color = new Color($colorString);
        }
        /* Weighted per visual impact */
        $grey = (int) round(
            $color->red * 0.299
            + $color->green * 0.587
            + $color->blue * 0.114
        );

        return new Color($grey, $grey, $grey);
    }

    /**
     * Saturates a color
     *
     * @param int $percent
     *
     * @return \Colorizr\models\Color
     */
    public function saturate($percent)
    {
        $percent = $this->sanitizePercentage($percent) / 100.0;
        $color2 = $this->greyscale();
        $red   = $percent * $this->color->red + (1.0 - $percent)
            * $color2->red;
        $green = $percent * $this->color->green + (1.0 - $percent)
            * $color2->green;
        $blue  = $percent * $this->color->blue + (1.0 - $percent)
            * $color2->blue;
        return new Color($red, $green, $blue);
    }

    /**
     * Desaturates a color
     *
     * @param int $percent
     *
     * @return \Colorizr\models\Color
     */
    public function desaturate($percent)
    {
        $percent = $this->sanitizePercentage($percent) / 100.0 * -1;
        $color2 = $this->greyscale();
        $red   = $percent * $this->color->red + (1.0 - $percent)
            * $color2->red;
        $green = $percent * $this->color->green + (1.0 - $percent)
            * $color2->green;
        $blue  = $percent * $this->color->blue + (1.0 - $percent)
            * $color2->blue;
        return new Color($red, $green, $blue);
    }

    /**
     * Multiplies two colors together
     *
     * @param String $colorString
     *
     * @return \Colorizr\models\Color
     */
    public function multiply($colorString)
    {
        $color2 = new Color($colorString);
        return $this->multiplyColors($this->color, $color2);
    }

    /**
     * Multiplies two colors together
     *
     * @param String $colorString
     *
     * @return \Colorizr\models\Color
     */
    public function screen($colorString)
    {
        $color2 = new Color($colorString);
        $red    = $this->color->red;
        $green  = $this->color->green;
        $blue   = $this->color->blue;

        $red2   = $color2->red;
        $green2 = $color2->green;
        $blue2  = $color2->blue;

        return new \Colorizr\models\Color(
            $this->screenChannel($red, $red2),
            $this->screenChannel($green, $green2),
            $this->screenChannel($blue, $blue2)
        );
    }

    /**
     * Overlays a color with a modifying color
     *
     * @param String $colorString Color String
     *
     * @return \Colorizr\models\Color
     */
    public function overlay($colorString)
    {
        $modifier = new Color($colorString);
        $product  = new Color(0, 0, 0);

        foreach (array('red', 'green', 'blue') as $channel) {
            if ($this->color->$channel >= 128) {
                $product->$channel = $this->screenChannel(
                    $this->color->$channel,
                    $modifier->$channel
                );
            } else {
                $product->$channel = $this->multiplyChannel(
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
    public function complementary()
    {
        return $this->hueShift(180);
    }

    /**
     * Shifts the hue by a degree
     *
     * @param int $degree Degree to shift 0 - 360
     *
     * @return \Colorizr\models\Color
     */
    public function hueShift($degree)
    {
        $color = clone $this->color;
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
    private function sanitizePercentage($percent)
    {
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
    private function multiplyColor($multiplier)
    {
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
    private function screenChannel($base, $mod)
    {
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
    private function multiplyChannel($base, $mod)
    {
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
    private function multiplyColors($base, $modifier)
    {
        return new Color(
            $this->multiplyChannel($base->red, $modifier->red),
            $this->multiplyChannel($base->green, $modifier->green),
            $this->multiplyChannel($base->blue, $modifier->blue)
        );
    }
}
