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
// DELETE Site
$routes->delete('/site/(:num)', 'SiteController::deleteSite/$1');

// GET All Blocks
$routes->get('/blocks', 'BlockController::getBlocks');
// GET Block By ID
$routes->get('/block/(:num)', 'BlockController::getBlockById/$1');
// GET Blocks By Site ID
$routes->get('/blocks/site/(:num)', 'BlockController::getBlocksBySiteId/$1');
// POST Block 
$routes->post('/blocks', 'BlockController::postBlock');
// PUT Block (
$routes->put('/blocks/(:num)', 'BlockController::updateBlock/$1');
// DELETE Block
$routes->delete('/block/(:num)', 'BlockController::deleteBlock/$1');


// GET all Flats
$routes->get('/flats', 'FlatController::getAllFlats');
// GET Flat By ID
$routes->get('/flat/(:num)', 'FlatController::getFlatById/$1');
// GET Flats By Block ID
$routes->get('flat/block/(:num)', 'FlatController::getFlatsByBlockId/$1');
// POST Flat
$routes->post('/flat', 'FlatController::postFlat');
// PUT Flat
$routes->put('/flat/(:num)', 'FlatController::updateFlat/$1');
// DELETE flat
$routes->delete('/flat/(:num)', 'FlatController::deleteFlat/$1');

//Filter
$routes->get('/flats/filter', 'FlatController::getFlatsByDateAndRoom');


// POST Add Debt
$routes->post('/admin/assign-debt', 'AdminController::PostAddDebt');
// POST Add Apartmnet For User
$routes->post('/admin/add-apartment', 'AdminController::adminAssignFlatToUser');

//GET debts
$routes->get('user/debts', 'UserController::GetDebts');
// POST Pay Debt
$routes->post('user/pay-debt', 'UserController::postPayDebt');
//GET Paid debts
$routes->get('user/paid-debts', 'UserController::GetPaidDebts');
//GET users filters
$routes->get('user/users-by-filters', 'UserController::getUsersByFilters');




