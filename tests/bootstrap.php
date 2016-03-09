<?php
date_default_timezone_set('America/New_York');

// Are we ready?
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    throw new RuntimeException("\nPlease run `composer install` to install dependencies.\n\n");
}

// Bootstrap our application with the Composer autoloader
$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->add('Colorizr', __DIR__ . '/../src');

