<?php

require_once "libs/router.php";
require_once "app/controllers/controller.php";
require_once "app/controllers/user.controller.php";
require_once "app/middlewares/jwt.middleware.php";

$router = new Router();

$router->addMiddleware(new JWT_middleware());

$router->addRoute("guitarras", "GET", "controller", "getGuitarras");
$router->addRoute("guitarras/:categoria", "GET", "controller", "getGuitarrasByCategoria");
$router->addRoute("guitarras/guitarra/:id", "GET", "controller", "getGuitarraByID");
$router->addRoute("guitarras", "POST", "controller", "addGuitarra");
$router->addRoute("guitarras/guitarra/:id", "PUT", "controller", "updateGuitarra");
$router->addRoute("guitarras/guitarra/:id", "DELETE", "controller", "deleteGuitarra");
$router->addRoute("categorias", "GET", "controller", "getCategorias");
$router->addRoute("user/token", "GET", "User_controller", "getToken");

$router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);