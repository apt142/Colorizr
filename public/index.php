<?php
/**
 * Index
 *
 * PHP version 5.3
 *
 * @category Colorizr
 * @package  Colorizr
 * @author   Cris Bettis <apt142@apartment142.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
const WEB_ROOT = __DIR__;

// Are we ready?
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    exit("\nPlease run `composer install` to install dependencies.\n\n");
}

/** @var $mode boolean Debug Mode? */
$isDebug = true;

date_default_timezone_set('America/New_York');

// Bootstrap our application with the Composer autoloader
$loader = require __DIR__ . '/../vendor/autoload.php';

// Setup the namespace for our own namespace
$loader->add('Colorizr', __DIR__ . '/../src');

use Symfony\Component\HttpFoundation\Request;

// Init Silex
$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../src/views',
));


// Path
$app->post(
    '/help',
        function() use($app) {
            // return $app->redirect('/help');
            header("Location: http://localhost:8008/help");
            die;
            // $app->abort(302, "Ack!");
        }
);

// Path
$app->get(
    '/help',
    function() use($app) {
        $controller = new Colorizr\controllers\Help($app);
        return $app->json($controller->help());
    }
);

$app->get(
    '/complementary/{colorString}',
    function($colorString) use($app) {
        $controller = new Colorizr\controllers\Color(
            $app,
            new \Colorizr\lib\ColorMath()
        );
        return $app->json($controller->complementary($colorString));
    }
);

$app->get(
    '/complementary/{colorString}/{degree}',
    function($colorString, $degree) use($app) {
        $controller = new Colorizr\controllers\Color(
            $app,
            new \Colorizr\lib\ColorMath()
        );
        return $app->json($controller->complementary($colorString, $degree));
    }
);

$app->get(
    '/adjacent/{colorString}',
    function($colorString) use($app) {
        $controller = new Colorizr\controllers\Color(
            $app,
            new \Colorizr\lib\ColorMath()
        );
        return $app->json($controller->adjacent($colorString));
    }
);

$app->get(
    '/adjacent/{colorString}/{degree}',
    function($colorString, $degree) use($app) {
        $controller = new Colorizr\controllers\Color(
            $app,
            new \Colorizr\lib\ColorMath()
        );
        return $app->json($controller->adjacent($colorString, $degree));
    }
);

$app->get(
    '/triad/{colorString}',
    function($colorString) use($app) {
        $controller = new Colorizr\controllers\Color(
            $app,
            new \Colorizr\lib\ColorMath()
        );
        return $app->json($controller->triad($colorString));
    }
);

$app->get(
    '/triad/{colorString}/{degree}',
    function($colorString, $degree) use($app) {
        $controller = new Colorizr\controllers\Color(
            $app,
            new \Colorizr\lib\ColorMath()
        );
        return $app->json($controller->triad($colorString, $degree));
    }
);

$app->get(
    '/quadtrad/{colorString}',
    function($colorString) use($app) {
        $controller = new Colorizr\controllers\Color(
            $app,
            new \Colorizr\lib\ColorMath()
        );
        return $app->json($controller->quadtrad($colorString));
    }
);

$app->get(
    '/quadtrad/{colorString}/{degree}',
    function($colorString, $degree) use($app) {
        $controller = new Colorizr\controllers\Color(
            $app,
            new \Colorizr\lib\ColorMath()
        );
        return $app->json($controller->quadtrad($colorString, $degree));
    }
);

$app->get(
    '/overlay/{colorString}/{filterColor}',
    function($colorString, $filterColor) use($app) {
        $controller = new Colorizr\controllers\Color(
            $app,
            new \Colorizr\lib\ColorMath()
        );
        return $app->json($controller->overlay($colorString, $filterColor));
    }
);

$app->get(
    '/multiply/{colorString}/{filterColor}',
    function($colorString, $filterColor) use($app) {
        $controller = new Colorizr\controllers\Color(
            $app,
            new \Colorizr\lib\ColorMath()
        );
        return $app->json($controller->multiply($colorString, $filterColor));
    }
);

$app->get(
    '/screen/{colorString}/{filterColor}',
    function($colorString, $filterColor) use($app) {
        $controller = new Colorizr\controllers\Color(
            $app,
            new \Colorizr\lib\ColorMath()
        );
        return $app->json($controller->screen($colorString, $filterColor));
    }
);

// Greyscale
$app->get(
    '/greyscale/{colorString}',
    function($colorString) use($app) {
        $controller = new Colorizr\controllers\Color(
            $app,
            new \Colorizr\lib\ColorMath()
        );
        return $app->json($controller->greyscale($colorString));
    }
);
// In case they want to spell it the other way
$app->get(
    '/grayscale/{colorString}',
    function($colorString) use($app) {
        $controller = new Colorizr\controllers\Color(
            $app,
            new \Colorizr\lib\ColorMath()
        );
        return $app->json($controller->greyscale($colorString));
    }
);

// In case they want to spell it the other way
$app->get(
    '/normalize/{colorString}/{$intensity}',
    function($colorString, $intensity) use($app) {
        $controller = new Colorizr\controllers\Color(
            $app,
            new \Colorizr\lib\ColorMath()
        );
        return $app->json($controller->normalize($colorString, $intensity));
    }
);

$app->get(
    '/lighten/{colorString}/{percent}',
    function($colorString, $percent) use($app) {
        $controller = new Colorizr\controllers\Color(
            $app,
            new \Colorizr\lib\ColorMath()
        );
        return $app->json($controller->lighten($colorString, $percent));
    }
);

$app->get(
    '/darken/{colorString}/{percent}',
    function($colorString, $percent) use($app) {
        $controller = new Colorizr\controllers\Color(
            $app,
            new \Colorizr\lib\ColorMath()
        );
        return $app->json($controller->darken($colorString, $percent));
    }
);

$app->get(
    '/saturate/{colorString}/{percent}',
    function($colorString, $percent) use($app) {
        $controller = new Colorizr\controllers\Color(
            $app,
            new \Colorizr\lib\ColorMath()
        );
        return $app->json($controller->saturate($colorString, $percent));
    }
);

$app->get(
    '/desaturate/{colorString}/{percent}',
    function($colorString, $percent) use($app) {
        $controller = new Colorizr\controllers\Color(
            $app,
            new \Colorizr\lib\ColorMath()
        );
        return $app->json($controller->desaturate($colorString, $percent));
    }
);

$app->get(
    '/random',
    function() use($app) {
        $controller = new Colorizr\controllers\Color(
            $app,
            new \Colorizr\lib\ColorMath()
        );
        return $app->json($controller->random());
    }
);

$app->get(
    '/theme/{colorString}',
        function($colorString) use($app) {
            $controller = new Colorizr\controllers\Color(
                $app,
                new \Colorizr\lib\ColorMath()
            );
            return $app->json($controller->theme($colorString));
        }
);


$app->get(
    '/',
    function() use($app) {
        $controller = new Colorizr\controllers\Theme();
        $vars = $controller->themeCues();
        return $app['twig']->render(
                           'theme-cues.twig',
                               $vars
        );
    }
);

$app->get(
    '/theme-cue',
        function() use($app) {
            $controller = new Colorizr\controllers\Theme();
            $vars = $controller->themeCues();

            return $app['twig']->render(
                'theme-cues.twig',
                $vars
            );
        }
);

$app->get(
    '/theme-cue/{colorString}',
    function($colorString) use($app) {
        $controller = new Colorizr\controllers\Theme();
        $vars = $controller->themeCues($colorString);

        return $app['twig']->render(
            'theme-cues.twig',
            $vars
        );
    }
);

$app->post(
    '/build/bootstrap',
     function (Request $request) use ($app) {
         $controller = new Colorizr\controllers\Theme();
         return $controller->buildBootstrap($request, $app);
     }
);



$app->error(function (\Exception $e, $code) use ($app, $isDebug) {
    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            if ($isDebug) {
                $message = $e->getMessage();
            } else {
                $message = 'We are sorry, but something went terribly wrong.';
            }
    }

    return $app->json(array('error' => $message));
});

$app->run();
