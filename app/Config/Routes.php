<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->group("", function ($routes) {
//---------- LOGIN
$routes->post("login", "Login::index");
$routes->get("check", "User::check");
//---------- USERS
$routes->post("register", "Register::index");
$routes->get("user", "User::index", ['filter' => 'authFilter']);
$routes->get("user/(:num)", "User::search/$1", ['filter' => 'authFilter']);
$routes->get("user/paged", "User::paged", ['filter' => 'authFilter']);
$routes->put("user/(:num)", "User::update/$1", ['filter' => 'authFilter']);
$routes->delete("user/(:num)", "User::delete/$1", ['filter' => 'authFilter']);
//---------- MENU
$routes->post("menu", "Menu::insert", ['filter' => 'authFilter']);
$routes->get("menu/paged", "Menu::paged", ['filter' => 'authFilter']);
$routes->get("menu", "Menu::index", ['filter' => 'authFilter']);
$routes->get("menu/(:num)", "Menu::search/$1", ['filter' => 'authFilter']);
$routes->put("menu/(:num)", "Menu::update/$1", ['filter' => 'authFilter']);
$routes->delete("menu/(:num)", "Menu::delete/$1", ['filter' => 'authFilter']);
//---------- PLATES
$routes->post("plates", "Plates::insert", ['filter' => 'authFilter']);
$routes->get("plates", "Plates::index", ['filter' => 'authFilter']);
$routes->get("plates/(:num)", "Plates::search/$1", ['filter' => 'authFilter']);
$routes->put("plates/(:num)", "Plates::update/$1", ['filter' => 'authFilter']);
$routes->delete("plates/(:num)", "Plates::delete/$1", ['filter' => 'authFilter']);
//---------- ATRIBUTOS
$routes->post("atributes", "Atributes::insert", ['filter' => 'authFilter']);
$routes->get("atributes", "Atributes::index", ['filter' => 'authFilter']);
$routes->get("atributes/(:num)", "Atributes::search/$1", ['filter' => 'authFilter']);
$routes->put("atributes/(:num)", "Atributes::update/$1", ['filter' => 'authFilter']);
$routes->delete("atributes/(:num)", "Atributes::delete/$1", ['filter' => 'authFilter']);
//---------- CLIENTES
$routes->post("client", "Client::insert", ['filter' => 'authFilter']);
$routes->get("client", "Client::index", ['filter' => 'authFilter']);
$routes->get("client/(:num)", "Client::search/$1", ['filter' => 'authFilter']);
$routes->put("client/(:num)", "Client::update/$1", ['filter' => 'authFilter']);
$routes->delete("client/(:num)", "Client::delete/$1", ['filter' => 'authFilter']);
});
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
