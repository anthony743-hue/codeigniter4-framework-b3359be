<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->group('livres', function($routes){
    $routes->get('', 'Livre::index');
    $routes->get('detail/(:num)', 'Livre::getDetailled/$1');
    $routes->get('add', 'Livre::add');
    $routes->post('add', 'Livre::add');
    $routes->post('delete/(:num)', 'Livre::delete/$1');
    $routes->get('pret/(:num)', 'Livre::pret/$1');
    $routes->post('pret/(:num)', 'Livre::pret/$1');
    $routes->get('retour/(:num)', 'Livre::retour/$1');
    $routes->post('retour/(:num)', 'Livre::retour/$1');
});