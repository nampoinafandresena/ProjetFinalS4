<?php

namespace Config;

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