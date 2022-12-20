<?php

/* 
    Define Krs Routes
*/
$routes->get('penempatan', '\Modules\Penempatan\Controllers\Penempatan::index');
$routes->post('penempatan', '\Modules\Penempatan\Controllers\Penempatan::add');
$routes->put('penempatan/(:num)', '\Modules\Penempatan\Controllers\Penempatan::edit/$1');
$routes->delete('penempatan/(:num)', '\Modules\Penempatan\Controllers\Penempatan::delete/$1');
