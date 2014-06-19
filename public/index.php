<?php
/**
 * Index
 *
 * PHP version 5.3
 *
 * @category Colorizr
 * @package  Colorizr
 * @author   Cris Bettis <cris.bettis@bettercarpeople.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

// Are we ready?
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    exit("\nPlease run `composer install` to install dependencies.\n\n");
}

$mode = 'debug';

// Bootstrap our application with the Composer autoloader
$loader = require __DIR__ . '/../vendor/autoload.php';

// Setup the namespace for our own namespace
$loader->add('Colorizr', __DIR__ . '../src' );

// Init Silex
$app = new Silex\Application();
$app['debug'] = true;

// Path
$app->get(
    '/',
    function() use($app) {
        return "Hello World!";
    }
);

$app->get(
    '/complimentary/{colorString}',
    function($colorString) use($app) {
        $controller = new Colorizr\controllers\Color(
            $app,
            new \Colorizr\lib\ColorMath()
        );
        return json_encode($controller->complimentary($colorString));
    }
);

$app->get(
    '/random',
    function() use($app) {
        $controller = new Colorizr\controllers\Color(
            $app,
            new \Colorizr\lib\ColorMath()
        );
        return json_encode($controller->random());
    }
);

$app->error(function (\Exception $e, $code) use ($mode) {
    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            if ($mode == 'debug') {
                $message = $e->getMessage();
            } else {
                $message = 'We are sorry, but something went terribly wrong.';
            }
    }

    return json_encode(array('error' => $message));
});

$app->run();
