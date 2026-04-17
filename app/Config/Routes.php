<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/livres', 'Livre::index');
$routes->get('/livres/(:num)', 'Livre::getDetailled/$1');
