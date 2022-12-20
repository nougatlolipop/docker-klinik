<?php

/* 
    Define Krs Routes
*/
$routes->get('pendaftaran', '\Modules\Pendaftaran\Controllers\Pendaftaran::index');
$routes->get('pendaftaran/(:any)', '\Modules\Pendaftaran\Controllers\Pendaftaran::index');
$routes->post('pendaftaran/pasien', '\Modules\Pendaftaran\Controllers\Pendaftaran::pasienAdd');
