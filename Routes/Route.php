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

		$Obtener_URL = array();
		$Obtener_relations = array();
		$Obtener_rel = array();
		$Obtener_relCompleta = array();
		$Obtener_tablas = array();
		$Obtener_tablas2 = array();
		$Obtener_type = array();
		$ObtenerCampos = array();
		$Filtro_linkTo = array();
		$Filtro_equalTo = array();

		$routesArray = explode("/",$_SERVER['REQUEST_URI']); // Obtiene un arreglo del parametro del dominio

		// https://www.miportalweb.org/curso-web/MarketPlace2/relations?rel=t_Products,t_Categories,t_Subcategories&type=product,category,subcategory&linkTo=url_subcategory&equalTo=home-audio-theathers

	
		$Obtener_URL = explode("?",$routesArray[3]);		
		$Obtener_relations = explode("&",$Obtener_URL[0]);
		//$Obtener_relations = $Obtener_relations[0];
		$Obtener_relCompleta = explode("=",$Obtener_URL[1]);
		$Obtener_rel= explode("=",$Obtener_URL[1]);
		//$Obtener_rel = $Obtener_rel[0];
		$Obtener_tablas = explode("&",$Obtener_relCompleta[1]);
		//$Obtener_tablas2 = $Obtener_tablas[0];
		$Obtener_type = $Obtener_tablas;
		$ObtenerCampos = explode("&",$Obtener_relCompleta[2]);
		//$ObtenerCampos = $ObtenerCampos[0];
		$Filtro_linkTo = $ObtenerCampos;
		$Filtro_equalTo = explode("&",$Obtener_relCompleta[3]);

		//$ObtenerCampos = explode("&",$ObtenerRelacion[2]);
		//$ObtenerFiltros = explode("&",$ObtenerRelacion[3]);
		
		//$Tabla = explode("?",$routesArray[3]);
			//echo '<pre>'; print_r($routesArray); echo '</pre>';
			//echo '<pre>'; print_r($Obtener_URL); echo '</pre>';
			echo '<pre>'; print_r($Obtener_relations[0]); echo '</pre>';
			echo '<pre>'; print_r($Obtener_relCompleta); echo '</pre>';
			echo '<pre>'; print_r($Obtener_rel[0]); echo '</pre>';
			echo '<pre>'; print_r($Obtener_tablas[0]); echo '</pre>';
			//echo '<pre>'; print_r($Obtener_tablas2); echo '</pre>';
			echo '<pre>'; print_r($Obtener_type[1]); echo '</pre>';
			echo '<pre>'; print_r($ObtenerCampos[0]); echo '</pre>';
			echo '<pre>'; print_r($Filtro_linkTo[1]); echo '</pre>';
			//echo '<pre>'; print_r($Filtro_equalTo); echo '</pre>';
			echo '<pre>'; print_r($Filtro_equalTo[0]); echo '</pre>';
			echo '<pre>'; print_r($Filtro_equalTo[1]); echo '</pre>';

			//echo '<pre>'; print_r($ObtenerTablas); echo '</pre>';
			//echo '<pre>'; print_r($ObtenerCampos); echo '</pre>';
			//echo '<pre>'; print_r($ObtenerFiltros); echo '</pre>';
			

			//echo '<pre>'; print_r($RelTabla); echo '</pre>';
			//echo '<pre>'; print_r($Filtros); echo '</pre>';
			return;			
			exit;
		

		// Se verifica si estan mandando las variables GETs "linkto", "equalTo", por lo que es que es con Filtro.
		//if ((isset($_GET["linkTo"])) && (isset($_GET["equalTo"])))
		//{
			// Se colocan que no vengan las variables globales GET "rel" y "type" cuando se realizan peticiones GET con Filtro con dos tablas.
			if ((isset($_GET["linkTo"])) && isset($_GET["equalTo"]) && !isset($_GET["rel"]) && !isset($_GET["type"]))
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
			else if ((isset($_GET["rel"])) && (isset($_GET["type"])) && (explode("?",$routesArray[3])[0]=="relations") && !isset($_GET["linkTo"]) && !isset($_GET["equalTo"]))
			{
				// =========================================================================
				// Peticiones GET entre tablas Relacionadas SIN Filtro
				// =========================================================================
			
				// Verifica si se esta realizando una relacion de tablas.
				// Que no venga las variables GET["linkTo"], GET["equalTo"]para el filtro  
				$response = new GetController();
				// $_GET["rel"] = Se envian las tablas que se van a relacionar
				// $_GET["type"] = Son los campos por el cual se van a relacionar.
				$response->getRelData($_GET["rel"],$_GET["type"]);
			}
			else if ((isset($_GET["rel"])) && (isset($_GET["type"])) && (explode("?",$routesArray[3])[0]=="relations") && (isset($_GET["linkTo"])) && (isset($_GET["equalTo"])))
			{
				// =========================================================================
				// Peticiones GET entre tablas Relacionadas CON Filtro
				// =========================================================================
			
				// Verifica si se esta realizando una relacion de tablas.
				
				$response = new GetController();
				// $_GET["rel"] = Se envian las tablas que se van a relacionar
				// $_GET["type"] = Son los campos por el cual se van a relacionar.
				$response->getRelFilterData($_GET["rel"],$_GET["type"],$_GET["linkTo"],$_GET["equalTo"]);
			}
			else // if ((isset($_GET["linkTo"])) && isset($_GET["equalTo"]) && !isset($_GET["rel"]) && !isset($_GET["type"]))		
			{
				

					// =========================================================================
					// Peticiones GET sin Filtro
					// =========================================================================
					if (!isset($_GET["linkTo"]) && !isset($_GET["linkTo"]))
					{
						$response = new GetController();
						// $routesArray[3] = Es donde viene el nombre de la Tabla.
						// Valida que existan las variables GET "linkTo" y "equalTo"
						$response->getData($routesArray[3]); // Muestra el contenido o mensaje de error cuando no existe la tabla
					}
					else
					{
						$json = array(
							'status' => 400,				
							'result' => "No existe(n) la(s) variable(s) GET para el Filtro"
						);
						// echo json_encode($json);
						// Ahora agregando un segundo parametro, para que se muestre en Postman o ThunderClient.
						//	echo json_encode($json,http_response_code(404));
							echo json_encode($json,http_response_code($json["status"]));
				
						return;				
					}

				

			} // if ((isset($_GET["linkTo"])) && isset($_GET["equalTo"]) && !isset($_GET["rel"]) && !isset($_GET["type"]))

		//} // if ((isset($_GET["linkTo"])) && (isset($_GET["equalTo"])))
		//else
		//{		
			/*	
			// Verifica si solo viene una tabla SIN Filtro
			
			$Tabla = array();
			$Tabla = explode("?",$routesArray[3]);
				echo '<pre>'; print_r($routesArray); echo '</pre>';
				echo '<pre>'; print_r($Tabla); echo '</pre>';
				return;			
			
			if ($Tabla[0] != 'relations')
			{
				// =========================================================================
				// Peticiones GET sin Filtro
				// =========================================================================	

				// Una sola tabla.
				$response = new GetController();
				// $routesArray[3] = Es donde viene el nombre de la Tabla.				
				$response->getData($routesArray[3]); // Muestra el contenido o mensaje de error cuando no existe la tabla
			}
			else
			{	
				$json = array(
					'status' => 400,				
					'result' => "No existe(n) la(s) variable(s) GET para el Filtro"
				);
				// echo json_encode($json);
				// Ahora agregando un segundo parametro, para que se muestre en Postman o ThunderClient.
				//	echo json_encode($json,http_response_code(404));
					echo json_encode($json,http_response_code($json["status"]));
		
				return;		
			}
			*/

		//} // if ((isset($_GET["linkTo"])) && (isset($_GET["equalTo"])))

		

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
