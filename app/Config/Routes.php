<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

// Hello routes
$routes->get('/hello', 'Home::hello');
$routes->get('/hello/(:segment)', 'Home::hello/$1');

// Activity 1
$routes->get('weather', 'Weather::index');

$routes->group(
    'api/v1',
    ['namespace' => 'App\Controllers\Api\V1', 'filter' => 'apikey'],
    static function ($routes) {
        $routes->post('weather/refresh', 'Weather::refresh');
        $routes->get('weather/logs', 'Weather::logs');
        $routes->get('weather/logs/(:num)', 'Weather::show/$1');
    }
);
