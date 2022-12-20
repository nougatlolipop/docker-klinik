<?php

/* 
    Define Krs Routes
*/
$routes->get('poli', '\Modules\Poli\Controllers\Poli::index');
$routes->post('poli', '\Modules\Poli\Controllers\Poli::add');
$routes->put('poli/(:any)', '\Modules\Poli\Controllers\Poli::edit/$1');
$routes->delete('poli/(:any)', '\Modules\Poli\Controllers\Poli::delete/$1');
