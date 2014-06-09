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
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
        $this->alpha = $alpha;


    }

    /**
     * Converts value to hex format
     *
     * @return string
     */
    public function toHex() {
        return dechex($this->red) . dechex($this->green) . dechex($this->blue);
    }

    /**
     * Converts value to rgb(#,#,#) format
     *
     * @return string
     */
    public function toRGB() {
        return 'rgb(' . $this->red . ',' . $this->green. ',' . $this->blue
            . ')';
    }

    /**
     * Converts value to rgba(#,#,#,#) format
     *
     * @return string
     */
    public function toRGBA() {
        return 'rgba(' . $this->red . ',' . $this->green. ',' . $this->blue
            . ',' . $this->alpha . ')';
    }
}
