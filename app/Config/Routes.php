<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('/admin', function ($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('prefixe', 'AdminController::prefixe');
    $routes->post('prefixe/add', 'AdminController::addPrefix');
    $routes->post('prefixe/toggle/(:num)', 'AdminController::togglePrefix/$1');
    $routes->post('prefixe/delete/(:num)', 'AdminController::deletePrefix/$1');
});
