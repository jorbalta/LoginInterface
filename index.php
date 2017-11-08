<?php 
	header("Content-type:text/html;charset=\"utf-8\"");
	
	/*Validamos la informacion ingresada en el formulario de login*/
	session_start();
	
	if(array_key_exists("id",$_COOKIE) && $_COOKIE['id'])
	{
		$_SESSION['id'] = $_COOKIE['id'];
	}
  	if(array_key_exists("id",$_SESSION) && $_SESSION['id'])
	{
		echo "<p align='left'> <a href='login.php?Logout=1'>Cerrar Sesion</a> </p>";
	}else{
		header("Location: login.php");
	}
	
	$error="";
	$mensajeExito="";
 
	if($_POST)
	{
		//comprobaciones sobre campos
		if(!$_POST['nombre']){
			$error .= "Es obligatorio que indique su nombre<br>";
			}
		if(!$_POST['username']){
			$error .= "Es obligatorio que indique su user-ID<br>";
			}
		if(!$_POST['correo']){
			$error .= "Es obligatorio una direccion de correo electronico<br>";
			}
		if(!$_POST['asunto']){
			$error .= "Es obligatorio indicar el asunto<br>";
			}
		if(!$_POST['descripcion']){
			$error .= "Es necesario escribir un mensaje<br>";
			}
		//filter_var: nos indica que la direccion de correo electronico es valida
		if($_POST['correo'] && filter_var($_POST['correo'],FILTER_VALIDATE_EMAIL) === false){
			$error .= "Correo electronico no valido<br>";
			}
		if($error != ""){
			$Merror = "<div class=\"alert alert-danger\" role=\"alert\"><strong>Hubo algun error en el formulario: ".$error."</strong></div>";
			}
			else{
				$emailA = "jorge.baltazares@outlook.com";
				$asunto = $_POST['asunto'];
				$contenido = $_POST['descripcion'];
				$cabeceras = "From: ".$_POST['correo'];
				if(mail($emailA,$asunto,$contenido,$cabeceras)){
					$mensajeExito = "<div class=\"alert alert-success\" role=\"alert\"><h3>Mensaje enviado con exito, en breve nos pondremos en contacto contigo pronto!</h3></div>";
					}
					else{
					$error = "<div class=\"alert alert-danger\" role=\"alert\"><h2>El mensaje no pudo ser enviado!!!</h2></div>";
					}
				}
	}
?>

<!DOCTYPE html>
<html lang="es">

<head>

		<meta charset="utf-8"/>
		<meta http-equiv="Content-type" content="text/html"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
			<title>IT SUPPORT</title>
	<link rel="stylesheet" href="jquery/jquery-ui/jquery-ui.css">
	<link rel="stylesheet" href="bootstrap4/css/bootstrap.min.css">


<style type="text/css">
	body{
		background-image:url("imagen1.jpg");
    }
	.jumbotron{
		background-color:#03C412;
		color:white;
		height:290px;
		width:100%;
	}
	#newTicket{
		display:none;
	}
</style>

</head>

<body>

<!-- Navigation bar -->

<div class="jumbotron jumbotron-fluid">
	<div class="container">
	<p class="display-2">Grupo AppWARE</p>
	<br>
    <a class="ocultarForm"><button class="display-4 btn">New Ticket</button></a>
    <button class="display-4 btn">Manage Tickets</button>	

	</div>
</div>

	<div id="error"> <?php echo $Merror; ?> </div>
	<div id="exito"> <?php echo $mensajeExito; ?> </div>
	<form method="POST" id="newTicket">
			<!-- nombre -->
			<fieldset class="form-group">
			<label for="nombre">Nombre</label>
			<input type="text" class="form-control" id="nombre" name="nombre">
			</fieldset>
			
			<!-- username  -->
			<fieldset class="form-group">
			<label for="username">Signum</label>
			<input type="text" class="form-control" id="username" name="username" placeholder="User-ID">
			</fieldset>
			
			<!-- email  -->
			<fieldset class="form-group">
			<label for="correo">Correo electronico</label>
			<input type="email" class="form-control" id="correo" name="correo" placeholder="Ej:****@dominio.com">
			<small class="text-muted">No compartiremos tu correo electronico con nadie</small>
			</fieldset>
	
			<!-- asunto  -->
			<fieldset class="form-group">
			<label for="asunto">Asunto</label>
			<input type="text" class="form-control" id="asunto" name="asunto">
			</fieldset>
			
			<!-- descripcion  -->
			<fieldset class="form-group">			
			<label for="descripcion">Describe de manera detallada el problema</label>
			<textarea class="form-control" id="descripcion" name="descripcion" rows="6"></textarea>
			</fieldset>
			
			<!-- Attached files !-->
			<div class="form-group">
			<label for="exampleInputFile">Attached Files</label>
			<input type="file" class="form-control-file" id="exampleInputFile" aria-describedby="fileHelp">
			<small id="fileHelp" class="form-text text-muted">Añade las capturas de pantalla del error si es posible.</small>
			</div>
			
			<input type="submit" id="submit" class="btn btn-primary" value="Enviar">
	</form>
<br><br>

<script src="jquery/jquery.min.js"></script>
<script src="jquery/jquery-ui/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
<script src="bootstrap4/js/bootstrap.min.js"></script>

<!-- Codigo Javascript/jquery !-->
<script type="text/javascript">
$(".ocultarForm").click(function(){
	$("#newTicket").toggle();
});
</script>

</body>
</html>
