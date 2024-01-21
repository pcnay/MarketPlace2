<?php
	// Para utilizar en la URL el nombre de las tablas.
	
	// Obtiene los parametros del dominio es decir : https://www.miportalweb.org(dominio) /(t_Categoria) Parametro
	//$routesArray = $_SERVER['REQUEST_URI']; // Obtiene parametros del Dominio
	//$routesArray2 = $_SERVER['HTTP_HOST']; // Obtiene el Dominio.

	// explode = Convierte una cadena en Arreglo.
	$routesArray = explode("/",$_SERVER['REQUEST_URI']); // Obtiene un arreglo del parametro del dominio

	/* 
		$routesArray = explode("/",$_SERVER['REQUEST_URI']); // Obtiene un arreglo del parametro del dominio

		echo '<pre>'; print_r($routesArray); echo '</pre>';
		return;
	
		[0] => 
    [1] => curso-web
    [2] => MarketPlace2
    [3] => kdjf
	*/

	$routesArray = array_filter($routesArray);
/*
	// Ahora es de eliminar el primer elemento del arreglo "array_filter()"
	$routesArray = array_filter($routesArray);
	echo '<pre>'; print_r($routesArray); echo '</pre>';
	return;

	 	[1] => curso-web
    [2] => MarketPlace2
    [3] => t_Products
*/

// ================================================
// Cuando no se hace ninguna peticion a la API, cuando no se agrega el nombre de Tabla.
// Ya que [2] = MarketPlace, [3] = t_Products
// =============================================
if (count($routesArray)== 2)
{
	$json = array(
		'status' => 404,
		'result' => "Not Found"
	);
	// echo json_encode($json);
	// Ahora agregando un segundo parametro, para que se muestre en Postman o ThunderClient.
	//	echo json_encode($json,http_response_code(404));
		echo json_encode($json,http_response_code($json["status"]));

	return;
}
else // Cuando se realiza la peticion a la API con una Tabla.
{
	// =============================================================
	// Se registra todas las peticiones HTTP: GET, POST, PUT, DELETE
	// =============================================================

	// ===========================================================
	// PETICIONES GET
	// ===========================================================

	if ((count($routesArray) == 3) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"]=="GET"))
	{
		/*
		$json = array(
			'status' => 200,
			'result' => "GET"
		);
		// echo json_encode($json);
		// Ahora agregando un segundo parametro, para que se muestre en Postman o ThunderClient.
		//	echo json_encode($json,http_response_code(404));
			echo json_encode($json,http_response_code($json["status"]));
	
		return;
		*/

		// =========================================================================
		// Peticiones GET con Filtro
		// =========================================================================

		// Se verifica si estan mandando las variables GETs "linkto", "equalTo", por lo que es que es con Filtro.
		if ((isset($_GET["linkTo"])) && isset($_GET["equalTo"]))
		{
			/*
				$json = array(
					'status' => 200,
					'result' => explode("?",$routesArray[3])[0] // Para solo extraer el nombre de la tabla
					/*
						t_Categories, 														indice 0 (Es el nombre de la tabla)
						linkTo=url_category&equalTo=homekitchen		indice 1
					

				);
				// echo json_encode($json);
				// Ahora agregando un segundo parametro, para que se muestre en Postman o ThunderClient.
				//	echo json_encode($json,http_response_code(404));
					echo json_encode($json,http_response_code($json["status"]));
		
				return;
			*/

			$response = new GetController();
			$response->getFilterData(explode("?",$routesArray[3])[0],$_GET["linkTo"],$_GET["equalTo"]);


		}
		else if ((isset($_GET["rel"])) && (isset($_GET["type"])) && (explode("?",$routesArray[3])[0]))
		{
			// =========================================================================
			// Peticiones GET entre tablas Relacionadas SIN Filtro
			// =========================================================================
		
			// Verifica si se esta realizando una relacion de tablas.
			$response = new GetController();
			// $_GET["rel"] = Se envian las tablas que se van a relacionar
			// $_GET["type"] = Son los campos por el cual se van a relacionar.
			$response->getRelData($_GET["rel"],$_GET["type"]);
			

		}
		else
		{
			// =========================================================================
			// Peticiones GET sin Filtro
			// =========================================================================
			$response = new GetController();
			// $routesArray[3] = Es donde viene el nombre de la Tabla.
			$response->getData($routesArray[3]); // Muestra el contenido o mensaje de error cuando no existe la tabla
		}



	} // if ((count($routesArray) == 3) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"]=="GET"))

	// ===========================================================
	// PETICIONES POST
	// ===========================================================

	if ((count($routesArray) == 3) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"]=="POST"))
	{
		$json = array(
			'status' => 200,
			'result' => "POST"
		);
		// echo json_encode($json);
		// Ahora agregando un segundo parametro, para que se muestre en Postman o ThunderClient.
		//	echo json_encode($json,http_response_code(404));
			echo json_encode($json,http_response_code($json["status"]));
	
		return;
	} // if ((count($routesArray) == 3)...$_SERVER["REQUEST_METHOD"]=="POST"))

	// ===========================================================
	// PETICIONES POST
	// ===========================================================

	if ((count($routesArray) == 3) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"]=="PUT"))
	{
		$json = array(
			'status' => 200,
			'result' => "PUT"
		);
		// echo json_encode($json);
		// Ahora agregando un segundo parametro, para que se muestre en Postman o ThunderClient.
		//	echo json_encode($json,http_response_code(404));
			echo json_encode($json,http_response_code($json["status"]));
	
		return;
	} // if ((count($routesArray) == 3)...$_SERVER["REQUEST_METHOD"]=="PUT"))

	// ===========================================================
	// PETICIONES DELETE
	// ===========================================================

	if ((count($routesArray) == 3) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"]=="DELETE"))
	{
		$json = array(
			'status' => 200,
			'result' => "DELETE"
		);
		// echo json_encode($json);
		// Ahora agregando un segundo parametro, para que se muestre en Postman o ThunderClient.
		//	echo json_encode($json,http_response_code(404));
			echo json_encode($json,http_response_code($json["status"]));
	
		return;
	} // if ((count($routesArray) == 3)...$_SERVER["REQUEST_METHOD"]=="PUT"))


} // Cuando se realiza la peticion a la API con una Tabla, con 


?>
