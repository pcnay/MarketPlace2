<?php
	// 0 = No mostrar errores en el navegador
	// 1 = Mostrar errores en el navegador
	//init_set('display_errors',1);

	// Se desea crear un nuevo archivo de errores
	//init_set("log_errors",1); 

	// Se desea crear un nuevo archivo de errores
	//init_set("error_log","c:/xampp/htdocs/errores/php_error_log"); 


	// Permitir los CORS en PHP
	// Con esto ya se podra recibir peticions HTTP(GET, POST,PUT, DELETE) desde cualquier otro servidor
	
	header('Access-Control-Allow-Origin: *');
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
	header('content-type: application/json; charset=utf-8');
			
	require_once "Controllers/Route.controller.php";
	require_once "Controllers/Get.controller.php";
	
	require_once "Models/Get.model.php";
	
	$index = new RoutesController();
	$index->index(); // Como no es metoddo estatico, al llamarse se ejecutara de inmediato.
	

?>
