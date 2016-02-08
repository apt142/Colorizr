<?php
/**
 * ThemeMath
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

use \Colorizr\models\Color as Color;

/**
 * Class ThemeMath
 *
 * Computes operations on a set of colors related to a theme.
 *
 * @category Colorizr\lib
 * @package default
 * @author   Cris Bettis <apt142@apartment142.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @link     http://apartment142.com
 */
class ThemeMath {

    const DEFAULT_PRIMARY = '#337ab7';
    const DEFAULT_INFO    = '#5bc0de';
    const DEFAULT_SUCCESS = '#5cb85c';
    const DEFAULT_WARNING = '#f0ad4e';
    const DEFAULT_DANGER  = '#d9534f';

    /**
     * Returns array of default theme color values
     *
     * @return array
     */
    public static function getDefaults() {
        return array(
            'primary' => self::DEFAULT_PRIMARY,
            'info'    => self::DEFAULT_INFO,
            'success' => self::DEFAULT_SUCCESS,
            'warning' => self::DEFAULT_WARNING,
            'danger'  => self::DEFAULT_DANGER
        );
    }

    /**
     * Builds the full action palette from a single color
     *
     * @param string|Color $color Color to build from
     *
     * @return Color[]
     */
    public function buildActionPalette($color) {
        $colorMath = new ColorMath();
        $primary = $colorMath->set($color);

        $themeMath = new ThemeMath();
        $defaults = ThemeMath::getDefaults();
        $info    = $themeMath->themeCue($color, $defaults['info']);
        $success = $themeMath->themeCue($color, $defaults['success']);
        $warning = $themeMath->themeCue($color, $defaults['warning']);
        $danger  = $themeMath->themeCue($color, $defaults['danger']);

        return array(
            'primary' => $primary,
            'info'    => $info,
            'success' => $success,
            'warning' => $warning,
            'danger'  => $danger
        );
    }

    /**
     * Builds the grey scale palette
     *
     * @param string|Color $color Color to build from
     *
     * @return Color[]
     */
    public function buildGreyPalette($color = null) {
        return array(
            'darkest'  => new Color('#000'),
            'darker'   => new Color('#222'),
            'dark'     => new Color('#333'),
            'medium'   => new Color('#555'),
            'light'    => new Color('#777'),
            'lighter'  => new Color('#eee'),
            'lightest' => new Color('#fff')

        );
    }

    /**
     * Action to deliver the actions palette
     *
     * @param $primaryColorString
     * @param $roleColorString
     *
     * @param string|Color $primaryColorString Primary color of the palette
     * @param string|Color $roleColorString    Color of the role's action
     *
     * @return \Colorizr\models\Color
     */
    public function themeCue($primaryColorString, $roleColorString) {
        $primary = new \Colorizr\models\Color($primaryColorString);
        $role = new \Colorizr\models\Color($roleColorString);
        $result = new \Colorizr\models\Color('#fff');

        $primaryHSL = $primary->toHSL();
        $roleHSL = $role->toHSL();

        $colorMath = new ColorMath();

        $colorMath->set(
            $result->fromHSL(
                 $roleHSL->h,
                 (0.75 * $primaryHSL->s + 12.5), // (50 * 0.25 + $primaryHSL->s * 0.75),
                 (0.6 * $primaryHSL->l + 20) // (50 * 0.40 + $primaryHSL->l * 0.60)
            )
        );

        return $colorMath->overlay('#888');
    }
}
