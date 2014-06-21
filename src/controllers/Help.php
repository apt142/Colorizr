<?php
/**
 * Help
 *
 * PHP version 5.3
 *
 * @category Help
 * @package  Helpizr
 * @author   Cris Bettis <cris.bettis@bettercarpeople.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

namespace Colorizr\controllers;

/**
 * Class Help
 *
 * Help Parent Class
 *
 * @category Help
 * @package  Helpizr
 * @author   Cris Bettis <cris.bettis@bettercarpeople.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

class Help {
    /** @var \Silex\Application $_app */
    private $_app;

    /**
     * Constructor
     *
     * @param \Silex\Application &$app Silex Application
     *
     * @return \Colorizr\controllers\Help
     */
    public function __construct(&$app) {
        $this->_app = $app;
    }

    /**
     * Action to deliver complementary Help
     *
     * @return array
     */
    public function help() {
        $result = array(
            'GET'    => array(
                '/complementary/{colorString}' => 'Returns a complementary color',
                '/adjacent/{colorString}' => 'Returns base color with adjacent colors',
                '/triad/{colorString}' => 'Returns base color with triad colors',
                '/quadtrad/{colorString}' => 'Returns base color with quatrad colors',
                '/greyscale/{colorString}' => 'Returns the color in greyscale color',
                '/lighten/{colorString}/{percent}' => 'Returns a lighter color',
                '/darken/{colorString}/{percent}' => 'Returns a darker color',
                '/saturate/{colorString}/{percent}' => 'Returns a saturated color',
                '/desaturate/{colorString}/{percent}' => 'Returns a desaturated color',
                '/overlay/{colorString}/{modifierColor}' => 'Returns the overlay product of the two colors',
                '/multiply/{colorString}/{modifierColor}' => 'Returns the multiply product of the two colors',
                '/screen/{colorString}/{modifierColor}' => 'Returns the screen product of the two colors',
                '/random' => 'Returns a random color'
            ),
            'POST'   => array(),
            'PUT'    => array(),
            'DELETE' => array(),
            'README' => array(
                'Color Strings are in hex with no # sign',
                'Percents are floats between 0 and 100'
            )
        );
        return $result;
    }

}
