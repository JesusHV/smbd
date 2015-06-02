<?php 

require '../vendor/autoload.php'; // Module dependencies
require '../vendor/mailer/Mailerclass.php';
require '../vendor/idiorm.php';
require '../vendor/Validator.php';
// == Initialize the app ==
$app = new \Slim\Slim();


// set 'json response' header
$app->contentType('application/json');

// idiorm config
ORM::configure('mysql:host=localhost; dbname=smbd');
ORM::configure('username','smbd');
ORM::configure('password','123');
// ORM::configure('return_result_sets', true);

// == Routes ==	
require 'routes/ventas.php';
require 'routes/productos.php';
require 'routes/clientes.php';

// == enable CORS ==
$app->response->headers->set('Access-Control-Allow-Origin', '*');
$app->response->headers->set('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS');
$app->response->headers->set('Access-Control-Allow-Headers', 'X-CSRF-Token, X-Requested-With, Accept, Accept-Version, Content-Length, Content-MD5, Content-Type, Date, X-Api-Version');

// == Run the app ==
$app->run();