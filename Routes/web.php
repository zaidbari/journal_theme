<?php

use \Core\View;
use \Core\Router;
/**
 * Routing
 */
$router = new Router();

// Add the routes
$router->res('/', 'Home@index');

$router->res('/current-issue', 'Articles@current');
$router->res('/latest-articles', 'Articles@latest');
$router->res('/article/[:id]', 'Articles@show');
$router->res('/article/html/[:id]', 'Articles@fulltext');
$router->res('/article/pdf/[:id]', 'Articles@pdf');

$router->res('/archives', 'Archives@index');

$router->res('/year-[:year]/volume-[:volume]/issue-[:issue]-[:id]', 'Issues@index');

$router->res('/page/[*:slug]', 'Pages@index');

$router->res('/receive-file', 'ReceiveFiles@index', 'POST');
//$router->res('/receive-html', 'ReceiveFiles@html', 'POST');



$router->onHttpError(function ($code) { View::render('error/index', ['code' => $code]); });;

// Dispatch the router
$router->dispatch();