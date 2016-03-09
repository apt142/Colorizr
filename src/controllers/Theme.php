<?php
/**
 * Theme
 *
 * PHP version 5.3
 *
 * @category Theme
 * @package  Themeizr
 * @author   Cris Bettis <apt142@apartment142.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

namespace Colorizr\controllers;

use Colorizr\lib\BootstrapBuilder;
use Colorizr\lib\ConfigLoader;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request as Request;


/**
 * Class Theme
 *
 * Theme Parent Class
 *
 * @category Theme
 * @package  Themeizr
 * @author   Cris Bettis <apt142@apartment142.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

class Theme {
    /** @var \Silex\Application $_app */
    private $app;

    /**
     * Action to deliver complementary Theme
     *
     * @param string $colorString Optional Color String
     *
     * @return array
     */
    public function themeCues($colorString = null)
    {
        $colorString = empty($colorString) ? 'bada55' : $colorString;
        $colorString = '#' . rtrim($colorString, '#');

        $fonts = ConfigLoader::loadConfig('fonts');

        $result = array(
            'color' => $colorString,
            'fonts' => $fonts
        );

        return $result;
    }

    /**
     * Build a bootstrap css based on the request variables
     *
     * @param Request     $request
     * @param Application $app
     *
     * @return mixed
     */
    public function buildBootstrap(Request $request, Application $app)
    {
        // Build bootstrap from the request variables
        $bootstrap = new BootstrapBuilder($app);

        return $app->json(
            array(
                'file_path' => $bootstrap->build($request)
            )
        );
    }
}
