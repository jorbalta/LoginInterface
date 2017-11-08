  <?php

	header("Content-type:text/html;charset=\"utf-8\"");
	
	//Conexion a la base de datos
	$link = mysqli_connect("shareddb1d.hosting.stackcp.net","Appware-3231b2b5","grupoappware20","Appware-3231b2b5");
	
	if(!$link)
		{
			echo "Error: No se pudo conectar a MYSQL.<br>".PHP_EOL;
			echo "Error de depuracion: <br>".mysqli_connect_errno().PHP_EOL;
			echo "Error de depuracion: <br>".mysqli_connect_error().PHP_EOL;
		}
		/*else{
			echo "Exito: Se realizo una conexion apropiada a MySQL!! La base de datos Appware es genial <br>".PHP_EOL;
			echo "Informacion del host: ".mysqli_get_host_info($link).PHP_EOL;
		}*/
	
?>