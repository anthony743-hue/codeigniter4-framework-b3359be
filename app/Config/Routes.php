<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->group('/livres', function($routes){
    $routes->get('', 'Livre::index');
    $routes->get('add', 'Livre::add');
    $routes->post('add', 'Livre::add');
    $routes->post('delete', 'Livre::delete');
    $routes->post('pret/(:id)', 'Livre::pret/$1');
    $routes->post('retour/(:id)', 'Livre::retour/$1');
    $routes->get('(:id)', 'Livre::getDetailled/$1');
});