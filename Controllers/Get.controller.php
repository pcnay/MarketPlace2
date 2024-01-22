<?php 
	// Se realiza todas las peticiones GET de la API result

	/*
	// Como se almacena la ejecucion de la funcion.
	// Por lo que se utiliza metodos "static"
		// Para la ejecucion inmediata y el nombre de la funcion debe ser con la palabra "static" static public function getData($table)

		$response = new GetController();
		$response->getData()

		// Para se requiera almacena la ejecucion de la funcion. 
		$reponse = GetModel::getData(parametros)
	*/

	class GetController
	{
		// Se ejecutaran de forma inmediata los métodos.
		// Por lo que no se utilizara metodos "static"
		
		
		// ===========================================================================
		// Peticiones GET sin Filtros
		// ============================================================================
		public function getData($table)
		{	
			/*		
			// Este bloque de codigo se utiliza para mostrar informacion en la pantalla.
			$json = array(
				'status' => 200,
				'result' => $table
			);
			
				echo json_encode($json,http_response_code($json["status"]));
		
			return;
			*/

				$return = new GetController();
				$linkTo = Null;
				$equalTo = Null;
				$type = Null;
				//fncResponse('getRelData',$rel,$type,$linkTo,$equalTo)
				$return->fncResponse('GetData',$table,$type,$linkTo,$equalTo);

		} // public function getData($table)


		// ===========================================================================
		// Peticiones GET CON Filtro
		// ============================================================================
		
		public function getFilterData($table,$linkTo,$equalTo)
		{
			$return = new GetController();
			//$rel = $table;
			$type = Null;
			//fncResponse('getRelData',$rel,$type,$linkTo,$equalTo)
			$return->fncResponse('getFilterData',$table,$type,$linkTo,$equalTo);
		}

		// ===========================================================================
		// Peticiones GET con tablas(2) relacionadas SIN Filtro
		// ============================================================================
		static public function getRelData($rel,$type)
		{
			// $rel = Son las tablas que se van a relacionar
			// $type = Por cual campo se van a relacionar la tabla, por lo general es : "id_product",....

			$return = new GetController();
			$linkTo = Null;
			$equalTo = Null;
			// //fncResponse('getRelData',$rel,$type,$linkTo,$equalTo)
			$return->fncResponse('getRelData',$rel,$type,$linkTo,$equalTo);

		} // 		static public function getRelData($rel,$type)

		// Respuestas del Controlador. Se crea esta funcion para no duplicar el codigo
		// No se coloca la palabra "Static" ya que no se ejecutara de forma inmediata almacenara la os datos.

		// ===========================================================================
		// Peticiones GET con tablas(2) relacionadas CON Filtro
		// ============================================================================
		public function getRelFilterData($rel,$type,$linkTo,$equalTo)
		{
			// $rel = Son las tablas que se van a relacionar
			// $type = Por cual campo se van a relacionar la tabla, por lo general es : "id_product",....
			// $linkTo = Es el campo por el cual se hara al filtrado
			// $equalTo = Es el valor del campos por el cual se hara el filtrado
			$return = new GetController();
			$return->fncResponse('getRelFilterData',$rel,$type,$linkTo,$equalTo);
		}

		public function fncResponse($nombre_funcion,$table,$type,$linkTo,$equalTo)
		{
			if ($nombre_funcion == 'GetData')
			{
				// Determinar si la tabla existe.
				$Table_found = new GetController();
				//echo '<pre>';print_r($Table_found->found_Table($table));echo'</pre>';
				//exit;
				if ($Table_found->found_Table($table) == 'S')
				{
					// Obtener la informacion desde la base de datos.
					$response = GetModel::getData($table);
					$json = array(
						'status' => 200,
						'total' => count($response),
						'results' => $response
					);			
				}
				else
				{
					$json = array(
						'status' => 400,					
						'results' => "Not Table Found",
						'method' => 'GetData'
					);				

				} // if ($Table_found->found_Table($table) == 'S')

				echo json_encode($json,http_response_code($json["status"]));		
				return;

			} // if ($nombre_funcion == 'GetData')
			
			if ($nombre_funcion == "getFilterData")
			{
				$Table_found = new GetController();
				$Field_found = new GetController();
	
				//echo '<pre>';print_r($Table_found->found_Table($table));echo'</pre>';
				//exit;
				$RelacionarTabla = 'N';
				if ($Table_found->found_Table($table) == 'S')
				{
					if ($Field_found->found_Field_Table($table,$linkTo,$RelacionarTabla) == 'S')
					{
						// Por si escriben mal la palabra "linkTo" y "equalTo"
						//if (isset($_GET["linkTo"]) && isset($_GET["equalTo"]))
						//{
							// Obtener la informacion desde la base de datos.
							$response = GetModel::getFilterData($table,$linkTo,$equalTo);				
		
							if (!empty($response))
							{
								$json = array(
									'status' => 200,
									'total' => count($response),
									'results' => $response
								);			
							}
							else
							{
								$json = array(
									'status' => 400,					
									'results' => "Not Datas"
								);				
							}
						//} // if (isset($_GET["linkTo"]) && isset($_GET["equalTo"]))
	
					} // if ($Field_found->found_Field_Table($linkTo) == 'S')
					else
					{
						$json = array(
							'status' => 400,					
							'results' => "Field of Table Not Found"
						);				
					}
	
					echo json_encode($json,http_response_code($json["status"]));		
					return;	
				}
				else
				{
					$json = array(
						'status' => 400,					
						'results' => "Not Table Found",
						'method' => 'getFilterData'
					);				
				}
				echo json_encode($json,http_response_code($json["status"]));		
				return;
	
			} // if ($nombre_funcion == "getFilterData")

			if ($nombre_funcion == "getRelData")
			{
				$Table_found = new GetController();
				$Field_found = new GetController();
	
				//echo '<pre>';print_r($Table_found->found_Table($table));echo'</pre>';
				//exit;
				
				$rel = $table; // Tablas a relacionarse(al menos 2)
				//echo '<pre>';print_r($rel);'echo </pre>';


				if ($Table_found->found_Table($rel) == 'S')
				{
					// Verificando que exista los campos 
					//$Arreglo_tablas = explode(",",$table);
					$Arreglo_campos = explode(",",$type);
					//echo '<pre>';print_r($Arreglo_tablas);echo'</pre>';
					
					$contador = 0;
					$Existe_Campo = 'N';
					
					//for($p=0;$p<count($Arreglo_tablas);$p++)
					//{						
						for ($k=0;$k<count($Arreglo_campos);$k++)
						{
							//echo '<pre>';print_r($nombre_Campo);echo'</pre>';
							$Tablas = Null;
							$RelacionarTabla = 'S';
							$Existe_Campo = $Field_found->found_Field_Table($Tablas,$Arreglo_campos[$k],$RelacionarTabla);
							if ($Existe_Campo == 'S')
							{
								$contador+=1;
							}
							//echo '<pre>';print_r($contador);echo'</pre>';
							//break;

						} // for ($k=0;$k<count($Arreglo_campos);$k++)

					if ($contador == count($Arreglo_campos)) 
					{
						// Obtener la informacion desde la base de datos.
						$response = GetModel::getRelData($rel,$type);
							
						if (!empty($response))
						{
							$json = array(
								'status' => 200,
								'total' => count($response),
								'results' => $response
							);			
						}
						else
						{
							$json = array(
								'status' => 200,					
								'results' => "Not Datas"
								
							);				
						}
	
					} // if ($contador == count($Arreglo_tablas)) 
					else
					{
						$json = array(
							'status' => 400,					
							'results' => "Field of Table Not Found",
							'method' => 'getRelData'
						);				
					}
	
					echo json_encode($json,http_response_code($json["status"]));		
					return;	
				}
				else // if ($Table_found->found_Table($rel) == 'S')
				{
					$json = array(
						'status' => 400,					
						'results' => "Not Table Found",
						'method' => 'getRelData'
					);				
				} // if ($Table_found->found_Table($rel) == 'S')
				echo json_encode($json,http_response_code($json["status"]));		
				return;
	
			} // if ($nombre_funcion == "getRelData")

			if ($nombre_funcion == "getRelFilterData")
			{
				$Table_found = new GetController();
				$Field_found = new GetController();
	
				//echo '<pre>';print_r($Table_found->found_Table($table));echo'</pre>';
				//exit;
				
				$rel = $table; // Tablas a relacionarse(al menos 2)
				//echo '<pre>';print_r($rel);'echo </pre>';


				if ($Table_found->found_Table($rel) == 'S')
				{
					// Verificando que exista los campos 
					//$Arreglo_tablas = explode(",",$table);
					$Arreglo_campos = explode(",",$type);
					//echo '<pre>';print_r($Arreglo_tablas);echo'</pre>';
					
					$contador = 0;
					$Existe_Campo = 'N';
					
					//for($p=0;$p<count($Arreglo_tablas);$p++)
					//{						
						for ($k=0;$k<count($Arreglo_campos);$k++)
						{
							//echo '<pre>';print_r($nombre_Campo);echo'</pre>';
							$Tablas = Null;
							$RelacionarTabla = 'S';
							$Existe_Campo = $Field_found->found_Field_Table($Tablas,$Arreglo_campos[$k],$RelacionarTabla);
							if ($Existe_Campo == 'S')
							{
								$contador+=1;
							}
							//echo '<pre>';print_r($contador);echo'</pre>';
							//break;

						} // for ($k=0;$k<count($Arreglo_campos);$k++)

					if ($contador == count($Arreglo_campos)) 
					{
						// Obtener la informacion desde la base de datos.
						$response = GetModel::getRelFilterData($rel,$type,$linkTo,$equalTo);
							
						if (!empty($response))
						{
							$json = array(
								'status' => 200,
								'total' => count($response),
								'results' => $response
							);			
						}
						else
						{
							$json = array(
								'status' => 200,					
								'results' => "Not Datas"								
							);				
						}
	
					} // if ($contador == count($Arreglo_tablas)) 
					else
					{
						$json = array(
							'status' => 400,					
							'results' => "Field of Table Not Found",
							'method' => 'getRelData'
						);				
					}
	
					echo json_encode($json,http_response_code($json["status"]));		
					return;	
				}
				else // if ($Table_found->found_Table($rel) == 'S')
				{
					$json = array(
						'status' => 400,					
						'results' => "Not Table Found",
						'method' => 'getRelData'
					);				
				} // if ($Table_found->found_Table($rel) == 'S')
				echo json_encode($json,http_response_code($json["status"]));		
				return;
	
			} // if ($nombre_funcion == "getRelFilterData")


		} // public function fncResponse($response,$nombre_funcion,$linkTo,$equalTo)

		// Verifica si la tabla existe
		static public function found_Table($table)
		{		
			$tablas = array('t_Categories','t_Disputes','t_Messages','t_Orders','t_Products','t_Sales','t_Stores','t_Subcategories','t_Users');

			$ArrayTablas = explode(",",$table);	// Para separar si viene mas de una tabla.
			//echo '<pre>';print_r($ArrayTablas); echo'</pre>';
			//return false;
			//exit;

			$found = 'N';			
			for ($k=0;$k<=count($tablas);$k++)
			{				
				for ($l=0;$l<count($ArrayTablas);$l++)
				{
					if (in_array($ArrayTablas[$l],$tablas))
					{
						$found = 'S';
						//break;			
					}
					else
					{
						$found = 'N';
						break;
					} // if (in_array($ArrayTablas[$l],$tablas))

				} //for ($l=0;$l<num_tablas_usadas;$l++)

				if ($found == 'S' || $found == 'N')
				{
					break;
				}

			} //for ($k=0;$k<=count($tablas);$k++)

			return $found;

		} // static public function found_Table($table)
	
		// Verifica si el Campo de la Tabla existe		
		static public function found_Field_Table($table,$Field,$RelacionarTabla)
		{			
			//echo '<pre>';print_r($table);echo'</pre>';
			if ($table == 't_Users')
			{
				$campos_tablas = array('id_user','rol_user','picture_user','displayname_user','username_user','password_user','email_user','country_user','city_user','phone_user','address_user','token_user','method_user','wishlist_user','date_created_user','date_updated_user','token_exp_user');
				$Buscar = 'S';
			} // if ($table == 't_Categories')
			elseif ($table == 't_Categories')
			{
				$campos_tablas = array('id_category','name_category','title_list_category','url_category','image_category','icon_category','views_category','date_created_category','date_updated_category');
				$Buscar = 'S';
			}
			elseif ($table == 't_Disputes')
			{
				$campos_tablas = array('id_dispute','id_order_dispute','stage_dispute','id_user_dispute','id_store_dispute','content_dispute','answer_dispute','date_created_dipute','date_update_dipute');
				$Buscar = 'S';
			}
			elseif ($table == 't_Messages')
			{
				$campos_tablas = array('id_message','id_product_message','id_user_message','id_store_message','content_message','answer_message','date_created_message','date_updated_message');
				$Buscar = 'S';
			}
			elseif ($table == 't_Orders')
			{
				$campos_tablas = array('id_order','id_product_order','id_store_order','id_user_order','details_order','quantity_order','price_order','email_order','country_order','city_order','phone_order','address_order','process_order','status_order','date_created_order','date_updated_order');
				$Buscar = 'S';
			}
			elseif ($table == 't_Products')
			{
				$campos_tablas = array('id_product ','feedback_product','state_product','id_store_product','id_category_product','id_subcategory_product','title_list_product','name_product','url_product','image_product','price_product','shipping_product','stock_product','delivery_time_product','offer_product','description_product','sumary_product','details_product','specifications_product','galley_product','video_product','top_banner_product','default_banner_product','horizontal_slider_product','vertical_slider_product','reviews_product','tags_product','sales_product','views_product','date_created_product','date_updated_product');
				$Buscar = 'S';
			}
			elseif ($table == 't_Sales')
			{
				$campos_tablas = array('id_sale','id_order_sale','unit_price_sale','commision_sale','payment_method_sale','id_payment_sale','status_sale','date_created_sale','date_updated_sale');
				$Buscar = 'S';
			}
			elseif ($table == 't_Stores')
			{
				$campos_tablas = array('id_store','id_user_store','name_store','url_store','logo_store','cover_store','about_store','abstract_store','email_store','country_store','city_store','address_store','phone_store','socialnetwork_store','products_store','date_created_store','date_updated_store');
				$Buscar = 'S';
			}
			elseif ($table == 't_Subcategories')
			{
				$campos_tablas = array('id_subcategory','id_category_subcategory','title_list_subcategory','name_subcategory','url_subcategory','image_subcategory','views_subcategory','date_created_subcategory','date_updated_subcategory');
				$Buscar = 'S';
			}
			elseif ($table == Null)
			{
				$campos_tablas = array('id_user','rol_user','picture_user','displayname_user','username_user','password_user','email_user','country_user','city_user','phone_user','address_user','token_user','method_user','wishlist_user','date_created_user','date_updated_user','token_exp_user','id_category','name_category','title_list_category','url_category','image_category','icon_category','views_category','date_created_category','date_updated_category','id_dispute','id_order_dispute','stage_dispute','id_user_dispute','id_store_dispute','content_dispute','answer_dispute','date_created_dipute','date_update_dipute','id_message','id_product_message','id_user_message','id_store_message','content_message','answer_message','date_created_message','date_updated_message','id_order','id_product_order','id_store_order','id_user_order','details_order','quantity_order','price_order','email_order','country_order','city_order','phone_order','address_order','process_order','status_order','date_created_order','date_updated_order','id_product','feedback_product','state_product','id_store_product','id_category_product','id_subcategory_product','title_list_product','name_product','url_product','image_product','price_product','shipping_product','stock_product','delivery_time_product','offer_product','description_product','sumary_product','details_product','specifications_product','galley_product','video_product','top_banner_product','default_banner_product','horizontal_slider_product','vertical_slider_product','reviews_product','tags_product','sales_product','views_product','date_created_product','date_updated_product','id_sale','id_order_sale','unit_price_sale','commision_sale','payment_method_sale','id_payment_sale','status_sale','date_created_sale','date_updated_sale','id_store','id_user_store','name_store','url_store','logo_store','cover_store','about_store','abstract_store','email_store','country_store','city_store','address_store','phone_store','socialnetwork_store','products_store','date_created_store','date_updated_store','id_subcategory','id_category_subcategory','title_list_subcategory','name_subcategory','url_subcategory','image_subcategory','views_subcategory','date_created_subcategory','date_updated_subcategory');
			}
			else
			{
				$Buscar = 'N';
			}
			
			if ($RelacionarTabla != 'S')
			{
				if ($Buscar == 'S')
				{		
					$ArrayFields = explode(",",$Field);	// Para separar los campos de la tabla.
					
					for ($k=0;$k<=count($campos_tablas);$k++)
					{				
						for ($l=0;$l<count($ArrayFields);$l++)
						{
							if (in_array($ArrayFields[$l],$campos_tablas))
							{
								$found = 'S';
								//break;			
							}
							else
							{
								$found = 'N';
								break;
							} // if (in_array($ArrayTablas[$l],$tablas))
			
						} //for ($l=0;$l<num_tablas_usadas;$l++)
			
						if ($found == 'S' || $found == 'N')
						{
							break;
						}
			
					} //for ($k=0;$k<=count($campos_tablas);$k++)		
			
				}	// if ($Buscar == 'S')
			
			} // if ($RelacionarTabla != 'S')
			else // // if ($RelacionarTabla != 'S')
			{				
				$found = 'N';
				// Cuando son tablas relacionadas
				$campo_Buscar ='id_'.$Field;
				
				if (in_array($campo_Buscar,$campos_tablas))
				{
					$found = 'S';					
				}
				//echo '<pre>';print_r($found); echo'</pre>';				

			} // if ($RelacionarTabla != 'S')		

			return $found;
		} // static public function found_Field_Table($table,$Field)

	} // 	class GetController

?>
