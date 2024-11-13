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

// GET All Blocks
$routes->get('/blocks', 'BlockController::getBlocks');
// GET Block By ID
$routes->get('/block/(:num)', 'BlockController::getBlockById/$1');
// GET Blocks By Site ID
$routes->get('/blocks/site/(:num)', 'BlockController::getBlocksBySiteId/$1');
// POST Block (Create)
$routes->post('/blocks', 'BlockController::postBlock');
// PUT Block (Update)
$routes->put('/blocks/(:num)', 'BlockController::updateBlock/$1');


