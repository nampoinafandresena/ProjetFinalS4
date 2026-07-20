<?php

namespace Config;

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

    $routes->get('bareme-frais', 'AdminController::baremeFrais');
    $routes->post('bareme-frais/add', 'AdminController::addBareme');
    $routes->post('bareme-frais/delete/(:num)', 'AdminController::deleteBareme/$1');
    $routes->post('bareme-frais/update/(:num)', 'AdminController::updateBareme/$1');
});
$routes = Services::routes();

// Page d'accueil
$routes->get('/', 'AuthController::index');

$routes->get('client/login', 'AuthController::clientLoginForm');
$routes->post('client/login', 'AuthController::clientLogin');
$routes->get('client/dashboard', 'Client\DashboardController::index');


$routes->post('client/depot', 'Client\OperationController::depot');
$routes->post('client/retrait', 'Client\OperationController::retrait');
$routes->post('client/transfert', 'Client\OperationController::transfert');


$routes->post('client/calculer-frais', 'Client\OperationController::calculerFrais');


$routes->get('client/history', 'Client\HistoryController::index');


$routes->get('operator/dashboard', 'Operator\DashboardController::index');

$routes->get('logout', 'AuthController::logout');
