<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Root route for the home page
$routes->get('/', 'Home::index');

// GET All Sites
$routes->get('/sites', 'SiteController::getSites');
// GET Sites By ID
$routes->get('/site/(:num)', 'SiteController::getSiteById/$1');
// POST Site
$routes->post('/sites', 'SiteController::postSite');
// PUT Site
$routes->put('/sites/(:num)', 'SiteController::updateSite/$1');



