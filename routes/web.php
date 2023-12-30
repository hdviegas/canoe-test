<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('funds', 'FundController@list');
$router->put('funds/{id}', 'FundController@update');
$router->post('funds', 'FundController@store');



