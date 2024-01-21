<?php
	class Connection
	{
		// Se utilizara un metodo estatico, ya que almacena la informacion, ya que no se requiere que se dispare inmediatamente como sucede con los metodos estaticos.
		static public function connect()
		{
			try
			{
				// Conexion a Base De Datos de forma segura.
				$link = new PDO("mysql:host=localhost;dbname=bd_marketplace","usuario_marketplace","MarketPlace*2023-05-02");

				$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);											
				
				$mitz="America/Tijuana";
				$tz = (new DateTime('now', new DateTimeZone($mitz)))->format('P');
				$link->exec("SET time_zone='$tz';");
				
				// Tildes, caracteres especiales, latinoamericano.
				$link->exec("set names utf8");
			}
			catch(PDOException $e)
			{
				if ($e->getCode() == '42502')

        echo "Syntax Error: ".$e->getMessage();

				//die("Error".$e->getMessage());
			}			
			return $link;
		}
	}
?>
