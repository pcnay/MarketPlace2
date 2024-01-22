<?php
	require_once "connection.php";
	
	/*
	// Como se almacena la ejecucion de la funcion.
	// Por lo que se utiliza metodos "static"
		// Para la ejecucion inmediata, y el nombre de la funcion debe ser con la palabra "static" static public function getData($table)

		$response = new GetController();
		$response->getData()

		// Para se requiera almacena la ejecucion de la funcion	y el nombre de la funcion NO debe llevar la palabra "static" public function getData($table)

		$reponse = GetModel::getData(parametros)
	*/

	class GetModel
	{
		// =======================================================================
		// Peticiones GET sin Filtro
		// =======================================================================
		static public function getData($table)
		{
			// Connection = Nombre Clase.
			// connect() = Es el metodo.
			$stmt = Connection::connect()->prepare("SELECT * FROM $table");
			$stmt->execute();
			// Con el parametro "FETCH_CLASS" solo mostrara los atributo, es decir los nombre de los campos de la tabla
			// return $stmt->fetchAll(); // Obtiene toda la informacion de la tabla.
			return $stmt->fetchAll(PDO::FETCH_CLASS); // Obtiene toda la informacion de la tabla.
		}

		static public function getFilterData($table,$linkTo,$equalTo)
		{
			// Connection = Nombre Clase.
			// connect() = Es el metodo.
			$stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $linkTo = :$linkTo");
			$stmt->bindParam(":".$linkTo,$equalTo,PDO::PARAM_STR);
			$stmt->execute();
			// Con el parametro "FETCH_CLASS" solo mostrara los atributo, es decir los nombre de los campos de la tabla
			// return $stmt->fetchAll(); // Obtiene toda la informacion de la tabla.
			return $stmt->fetchAll(PDO::FETCH_CLASS); // Obtiene toda la informacion de la tabla.

		}

		// Peticiones Get Tablas Relacionadas SIN filtro		
		static public function getRelData($rel,$type)
		{
			// $rel = Son las tablas que se van a relacionar
			// $type = Por cual campo se van a relacionar la tabla, por lo general es : "id_product",....


			// t_Categories = Tabla Padre
			// t_Products = Tabla Hijo
			
			//$stmt = Connection::connect()->prepare("SELECT * FROM t_Categories INNER JOIN t_Products ON t_Categories.id_category = t_Products.id_category_product ");

			// Se van a separar las tablas que vienen en la variable "$rel"
			$relArray = explode(",",$rel); // Cuantas tablas
			$typeArray = explode(",",$type); // Cuantos campos de la tablas
			//echo '<pre>';print_r($relArray);echo'</pre>';

			// Relacionando dos tablas 
			if ((count($relArray) == 2) && (count($typeArray) == 2))
			{
				// https://www.miportalweb.org/curso-web/MarketPlace2/relations?rel=t_Products,t_Categories&type=product,category

				$On1 = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; 	// "t_Products.id_category_product";
				$On2 = $relArray[1].".id_".$typeArray[1];

				// Las relaciones se establecera de la Tabla que tenga el Id principal a la tabla del Id secundario.
				$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1 = $On2");
			} // if ((count($relArray) == 2) && (count($typeArray) == 2))

			// Relacionando tres tablas 
			if ((count($relArray) == 3) && (count($typeArray) == 3))
			{
				//https://www.miportalweb.org/curso-web/MarketPlace2/relations?rel=t_Products,t_Categories,t_Subcategories&type=product,category,subcategory

				/*
					$relArray[0] = "t_Categories"
					$relArray[1] = "t_Subcategories"
					$relArray[2] = "t_Products"

					$typeArray[0] = "category"
					$typeArray[1] = "subcategory"
					$typeArray[2] = "product"
				*/

				/*
				$On1 = $relArray[0].".id_".$typeArray[0]; // t_Categories.id_category				
				$On2 = $relArray[1].".id_".$typeArray[0]."_".$typeArray[1]; //t_Subcategories.id_category_subcategory			
				
				$On3 = $relArray[2].".id_".$typeArray[0]."_".$typeArray[2]; //t_Products.id_category_subcategory

				*/

				$On1a = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; 	// "t_Products.id_category_product";
				$On1b = $relArray[1].".id_".$typeArray[1];										// "t_Categories.id_category";
				$On2a = $relArray[0].".id_".$typeArray[2]."_".$typeArray[0]; 	//t_Products.id_subcategory_product";
				$On2b = $relArray[2].".id_".$typeArray[2];										// "t_Subcategories.id_subcategory";


				//$stmt = Connection::connect()->prepare("SELECT * FROM t_Categories INNER JOIN t_Subcategories ON t_Categories.id_category = t_Subcategories.id_category_subcategory INNER JOIN t_Products ON t_Categories.id_category = t_Products.id_category_product");


				$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1a = $On1b INNER JOIN $relArray[2] ON $On2a = $On2b");

				// Las relaciones se establecera de la Tabla que tenga el Id principal a la tabla del Id secundario.
				//$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1 = $On2");

			} // if ((count($relArray) == 3) && (count($typeArray) == 3))

			// Relacionando cuatro tablas 
			if ((count($relArray) == 4) && (count($typeArray) == 4))
			{
				//https://www.miportalweb.org/curso-web/MarketPlace2/relations?rel=t_Products,t_Categories,t_Subcategories,t_Stores&type=product,category,subcategory,store
				/*
					$relArray[0] = "t_Products"
					$relArray[1] = "t_Categories"
					$relArray[2] = "t_Subcategories"
					$relArray[3] = "t_Stores"

					$typeArray[0] = "product"
					$typeArray[1] = "category"
					$typeArray[2] = "subcategory"
					$typeArray[3] = "store"
				*/

				/*
					Relaciones de tablas
					t_Products.id_category_product = t_Categories.id_category
					t_Products.id_subcategory.product = t_Subcategories.id_subcategory 
					t_Products.id_store.product = t_Stores.id_store 
				*/

				// Se redefine las variables:
				$On1a = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; 	// "t_Products.id_category_product";
				$On1b = $relArray[1].".id_".$typeArray[1];										// "t_Categories.id_category";
				$On2a = $relArray[0].".id_".$typeArray[2]."_".$typeArray[0]; 	//t_Products.id_subcategory_product";
				$On2b = $relArray[2].".id_".$typeArray[2];										// "t_Subcategories.id_subcategory";
				$On3a = $relArray[0].".id_".$typeArray[3]."_".$typeArray[0];	//"t_Products.id_store_product";
				$On3b = $relArray[3].".id_".$typeArray[3];										//"t_Stores.id_store";

				//$stmt = Connection::connect()->prepare("SELECT * FROM t_Categories INNER JOIN t_Subcategories ON t_Categories.id_category = t_Subcategories.id_category_subcategory INNER JOIN t_Products ON t_Categories.id_category = t_Products.id_category_product");


				$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1a = $On1b INNER JOIN $relArray[2] ON $On2a = $On2b INNER JOIN $relArray[3] ON $On3a = $On3b");

				// Las relaciones se establecera de la Tabla que tenga el Id principal a la tabla del Id secundario.
				//$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1 = $On2");
			} // if ((count($relArray) == 4) && (count($typeArray) == 4))

			$stmt->execute();
			
			return $stmt->fetchAll(PDO::FETCH_CLASS); // Obtiene toda la informacion de la tabla.

		} // static public function getRelData($rel,$type)

		// ===========================================================================
		// Se Inicia el duplicado de las peticiones GET con Filtro
		// ===========================================================================

		// Peticiones Get Tablas Relacionadas CON filtro		
		static public function getRelFilterData($rel,$type,$linkTo,$equalTo)
		{
			// $rel = Son las tablas que se van a relacionar
			// $type = Por cual campo se van a relacionar la tabla, por lo general es : "id_product",....


			// t_Categories = Tabla Padre
			// t_Products = Tabla Hijo
			
			//$stmt = Connection::connect()->prepare("SELECT * FROM t_Categories INNER JOIN t_Products ON t_Categories.id_category = t_Products.id_category_product ");

			// Se van a separar las tablas que vienen en la variable "$rel"
			$relArray = explode(",",$rel); // Cuantas tablas
			$typeArray = explode(",",$type); // Cuantos campos de la tablas
			//echo '<pre>';print_r($relArray);echo'</pre>';

			// Relacionando dos tablas 
			if ((count($relArray) == 2) && (count($typeArray) == 2))
			{
				// https://www.miportalweb.org/curso-web/MarketPlace2/relations?rel=t_Products,t_Categories&type=product,category

				$On1 = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; 	// "t_Products.id_category_product";
				$On2 = $relArray[1].".id_".$typeArray[1];

				// Las relaciones se establecera de la Tabla que tenga el Id principal a la tabla del Id secundario.
				$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1 = $On2 WHERE $linkTo = :$linkTo ");				
			} // if ((count($relArray) == 2) && (count($typeArray) == 2))

			// Relacionando tres tablas 
			if ((count($relArray) == 3) && (count($typeArray) == 3))
			{
				//https://www.miportalweb.org/curso-web/MarketPlace2/relations?rel=t_Products,t_Categories,t_Subcategories&type=product,category,subcategory

				/*
					$relArray[0] = "t_Categories"
					$relArray[1] = "t_Subcategories"
					$relArray[2] = "t_Products"

					$typeArray[0] = "category"
					$typeArray[1] = "subcategory"
					$typeArray[2] = "product"
				*/

				/*
				$On1 = $relArray[0].".id_".$typeArray[0]; // t_Categories.id_category				
				$On2 = $relArray[1].".id_".$typeArray[0]."_".$typeArray[1]; //t_Subcategories.id_category_subcategory			
				
				$On3 = $relArray[2].".id_".$typeArray[0]."_".$typeArray[2]; //t_Products.id_category_subcategory

				*/

				$On1a = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; 	// "t_Products.id_category_product";
				$On1b = $relArray[1].".id_".$typeArray[1];										// "t_Categories.id_category";
				$On2a = $relArray[0].".id_".$typeArray[2]."_".$typeArray[0]; 	//t_Products.id_subcategory_product";
				$On2b = $relArray[2].".id_".$typeArray[2];										// "t_Subcategories.id_subcategory";


				//$stmt = Connection::connect()->prepare("SELECT * FROM t_Categories INNER JOIN t_Subcategories ON t_Categories.id_category = t_Subcategories.id_category_subcategory INNER JOIN t_Products ON t_Categories.id_category = t_Products.id_category_product");


				$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1a = $On1b INNER JOIN $relArray[2] ON $On2a = $On2b WHERE $linkTo = :$linkTo");			

				// Las relaciones se establecera de la Tabla que tenga el Id principal a la tabla del Id secundario.
				//$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1 = $On2");

			} // if ((count($relArray) == 3) && (count($typeArray) == 3))

			// Relacionando cuatro tablas 
			if ((count($relArray) == 4) && (count($typeArray) == 4))
			{
				//https://www.miportalweb.org/curso-web/MarketPlace2/relations?rel=t_Products,t_Categories,t_Subcategories,t_Stores&type=product,category,subcategory,store
				/*
					$relArray[0] = "t_Products"
					$relArray[1] = "t_Categories"
					$relArray[2] = "t_Subcategories"
					$relArray[3] = "t_Stores"

					$typeArray[0] = "product"
					$typeArray[1] = "category"
					$typeArray[2] = "subcategory"
					$typeArray[3] = "store"
				*/

				/*
					Relaciones de tablas
					t_Products.id_category_product = t_Categories.id_category
					t_Products.id_subcategory.product = t_Subcategories.id_subcategory 
					t_Products.id_store.product = t_Stores.id_store 
				*/

				// Se redefine las variables:
				$On1a = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; 	// "t_Products.id_category_product";
				$On1b = $relArray[1].".id_".$typeArray[1];										// "t_Categories.id_category";
				$On2a = $relArray[0].".id_".$typeArray[2]."_".$typeArray[0]; 	//t_Products.id_subcategory_product";
				$On2b = $relArray[2].".id_".$typeArray[2];										// "t_Subcategories.id_subcategory";
				$On3a = $relArray[0].".id_".$typeArray[3]."_".$typeArray[0];	//"t_Products.id_store_product";
				$On3b = $relArray[3].".id_".$typeArray[3];										//"t_Stores.id_store";

				//$stmt = Connection::connect()->prepare("SELECT * FROM t_Categories INNER JOIN t_Subcategories ON t_Categories.id_category = t_Subcategories.id_category_subcategory INNER JOIN t_Products ON t_Categories.id_category = t_Products.id_category_product");


				$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1a = $On1b INNER JOIN $relArray[2] ON $On2a = $On2b INNER JOIN $relArray[3] ON $On3a = $On3b WHERE $linkTo = :$linkTo");

				// Las relaciones se establecera de la Tabla que tenga el Id principal a la tabla del Id secundario.
				//$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1 = $On2");
			} // if ((count($relArray) == 4) && (count($typeArray) == 4))

			$stmt->bindParam(":".$linkTo,$equalTo,PDO::PARAM_STR);
			$stmt->execute();
			
			return $stmt->fetchAll(PDO::FETCH_CLASS); // Obtiene toda la informacion de la tabla.

		} // static public function getRelData($rel,$type)



		// ===========================================================================
		// Se Termina el duplicado de las peticiones GET con Filtro
		// ===========================================================================

		
	} // class GetModel
?>