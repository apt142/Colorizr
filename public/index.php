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
    '/complimentary/:colorString',
    function() use($app) {
        $controller = new Colorizr\controllers\colorFunctions(
            $app,
            new \Colorizr\lib\ColorMath()
        );
        return json_encode($controller->complimentary($colorString));
    }
);

$app->error(function (\Exception $e, $code) {
    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }

    return new Response($message);
});

$app->run();
