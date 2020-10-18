<?php
header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header("Access-Control-Allow-Methods: GET, POST, PUT,DELETE");
header('Content-type:application/json');
require_once 'model/articuloModel.php';
require_once 'model/userModel.php';

require_once 'controller/routesController.php';
require_once 'controller/usersController.php';
require_once 'controller/articulosController.php';

RoutesController::index();
