<?php

namespace Config;

$routes = Services::routes();

// Routes par défaut
$routes->get('/', 'AuthController::index');

// Auth Client
$routes->get('login', 'AuthController::index');
$routes->get('client/login', 'AuthController::clientLoginForm');
$routes->post('client/login', 'AuthController::clientLogin');
$routes->get('logout', 'AuthController::logout');

// ============================================
// CLIENT - PROTÉGÉ PAR AUTH
// ============================================
$routes->group('client', function($routes) {
    $routes->get('dashboard', 'Client\DashboardController::index');
    $routes->get('history', 'Client\HistoryController::index');
    $routes->post('depot', 'Client\OperationController::depot');
    $routes->post('retrait', 'Client\OperationController::retrait');
    $routes->post('transfert', 'Client\OperationController::transfert');
    $routes->post('calculer-frais', 'Client\OperationController::calculerFrais');
});

// ============================================
// ADMIN - SANS FILTRE AUTH (ACCÈS DIRECT)
// ============================================
$routes->group('admin', function($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('prefixe', 'AdminController::prefixe');
    $routes->post('prefixe/add', 'AdminController::addPrefix');
    $routes->post('prefixe/toggle/(:num)', 'AdminController::togglePrefix/$1');
    $routes->post('prefixe/delete/(:num)', 'AdminController::deletePrefix/$1');
    $routes->get('bareme-frais', 'AdminController::baremeFrais');
    $routes->post('bareme-frais/add', 'AdminController::addBareme');
    $routes->post('bareme-frais/delete/(:num)', 'AdminController::deleteBareme/$1');
    $routes->post('bareme-frais/update/(:num)', 'AdminController::updateBareme/$1');
    $routes->get('operateurs', 'AdminController::operateurs');
    $routes->post('operateur/update/(:num)', 'AdminController::updateOperateur/$1');
});

// ============================================
// OPERATOR - SANS FILTRE AUTH (ACCÈS DIRECT)
// ============================================
$routes->group('operator', function($routes) {
    $routes->get('gains', 'Operator\GainsController::index');
    $routes->get('clients', 'AdminController::clients');
});