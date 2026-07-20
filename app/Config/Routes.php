<?php

namespace Config;

$routes = Services::routes();

// ============================================
// PAGE D'ACCUEIL
// ============================================
$routes->get('/', 'AuthController::index');

// ============================================
// ROUTES CLIENT
// ============================================
$routes->get('client/login', 'AuthController::clientLoginForm');
$routes->post('client/login', 'AuthController::clientLogin');
$routes->get('client/dashboard', 'Client\DashboardController::index');

$routes->post('client/depot', 'Client\OperationController::depot');
$routes->post('client/retrait', 'Client\OperationController::retrait');
$routes->post('client/transfert', 'Client\OperationController::transfert');
$routes->post('client/calculer-frais', 'Client\OperationController::calculerFrais');

$routes->get('client/history', 'Client\HistoryController::index');

// ============================================
// ROUTES ADMIN / OPÉRATEUR
// ============================================
$routes->group('admin', function ($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('prefixe', 'AdminController::prefixe');
    $routes->post('prefixe/add', 'AdminController::addPrefix');
    $routes->post('prefixe/delete/(:num)', 'AdminController::deletePrefix/$1');

    $routes->get('bareme-frais', 'AdminController::baremeFrais');
    $routes->post('bareme-frais/add', 'AdminController::addBareme');
    $routes->post('bareme-frais/delete/(:num)', 'AdminController::deleteBareme/$1');
    $routes->post('bareme-frais/update/(:num)', 'AdminController::updateBareme/$1');
});

// Routes des gains
$routes->get('operator/gains', 'Operator\GainsController::index');
// Route clients
$routes->get('operator/clients', 'AdminController::clients');

// ============================================
// DÉCONNEXION
// ============================================
$routes->get('logout', 'AuthController::logout');