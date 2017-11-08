<?php

	header("Content-type:text/html;charset=\"utf-8\"");
	
	session_start();
	$error = "";
	if(array_key_exists("Logout",$_GET))
	{
	//viene de la pagina index.php
	session_unset();
	setcookie("id","",time()-60*60);
	$_COOKIE['id']="";
	}else if((array_key_exists("id",$_SESSION) AND $_SESSION['id']) OR (array_key_exists("id",$_COOKIE) AND $_COOKIE['id'])){
		header("Location: index.php");
	}
	
	if(array_key_exists("submit",$_POST))
	{
		//Conexion a la base de datos
		include("conexionBD.php");
		
		$usuario = mysqli_real_escape_string($link,$_POST['user']);
		$password = mysqli_real_escape_string($link,$_POST['pass']);
		
		if(!$_POST['user'])
		{
			$error .= "<br>Usuario requerido!!";
		}
		if(!$_POST['pass'])
		{
			$error .= "<br>Contraseña requerida!!";
		}
		if($error != "")
		{
			$error = "<div class=\"alert alert-danger\" role=\"alert\"><p>Hubo algun error en el formulario!!!".$error."</p></div>";
		}else{
			
			if($_POST['registro']=='1')
			{
			//Verificamos si el usuario ya esta registrado
			$query = "SELECT clave FROM usuarios WHERE username='$usuario' LIMIT 1";
			$resultado = mysqli_query($link,$query);
	
			$filas = mysqli_num_rows($resultado);
	
			if($filas > 0){
				echo "<div class='alert alert-info' role='alert'><p>Usuario ya registrado</p></div>";
			}else{
					//Agregar registros a la tabla usuarios
					$query = "INSERT INTO usuarios(username,passwd) VALUES ('$usuario','$password')";
					if(!mysqli_query($link,$query)){
						$error="<p>No hemos podido completar el registro, por favor intentelo mas tarde!!!</p>";
					}else{
					//Actualizar el almacenamiento de la contraseña
					$query = "UPDATE usuarios SET passwd='".md5(md5(mysqli_insert_id($link)).$_POST['pass'])."'WHERE clave=".mysqli_insert_id($link)." LIMIT 1";
					mysqli_query($link,$query);
					
					$_SESSION['id']=mysqli_insert_id($link);
					
					if($_POST['permanecerIniciada']=='1'){
						setcookie("id",mysqli_insert_id($link),time()+60*60*24*365);
					}
                        header("Location: index.php");
					}
				 }				 
			}
			else
			{
			//Comprobamos el inicio de sesion
				$query = "SELECT * FROM usuarios WHERE username='$usuario'";
				$result = mysqli_query($link,$query);
				$fila = mysqli_fetch_array($result);
				if(isset($fila)){
					$passHash = md5(md5($fila['clave']).$_POST['pass']);
					if($passHash == $fila['passwd']){
						//usuario autenticado
						$_SESSION['id']=$fila['clave'];
						if($_POST['permanecerIniciada']=='1'){
							setcookie("id",$fila['clave'],time()+60*60*24*365);
						}
                            header("Location: index.php");                        

					}else{
						$error="<h2>El Usuario/Contraseña no pudo ser encontrado!!</h2>";
					}
				}else{
					$error="<h2>El Usuario/Contraseña no pudo ser encontrado!!</h2>";
				}
			}

		    }
	}
		mysqli_close($link);
?>

<!-- Código HTML !-->
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="Content-type" content="text/html" />
		<title>AppTicket Tool</title>
	<link rel="stylesheet" href="jquery/jquery-ui/jquery-ui.css">
	<link rel="stylesheet" href="bootstrap4/css/bootstrap.min.css">
	
	<style type="text/css">
	@import url('https://fonts.googleapis.com/css?family=Hind+Guntur');
		html{
			background:url(cuerda.jpg) no-repeat center center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
		}
		body{
			background:none;
		}
		.jumbotron
		{
			margin-top:150px;
			margin-bottom:150px;
			background-color:#A90B04;
			background-size:cover;
			color:white;
		}
		#formularioRegistro{
			display:none;
		}
		.alternarFormularios{
			font-weight:bold;
		}
		#estilo{
			font-family:'Hind Guntur', sans-serif;
			font-size:35px;
		}
	</style>
</head>

<body>

<div class="jumbotron jumbotron-fluid">	
<div class="container">
	<h1 class="display-3">AppTicket Tool</h1>
	<br><br><br>
	
	<!--<div id="mError"></div>!-->

<!-- FORMULARIO DE REGISTRO !-->
	<form method="POST" id="formularioRegistro">

	<p id="estilo">Si no te haz registrado aun, ¡Hazlo ahora!</p>
	
	<fieldset class="form-group">
	<label for="user">Usuario</label>
	<input type="text" class="form-control" name="user" id="user" placeholder="Ingresa tu nombre de usuario"> 
	</fieldset>
	
	<fieldset class="form-group">
	<label for="pass">Contraseña</label>
	<input type="password" class="form-control" name="pass" id="pass" placeholder="Ingresa tu contraseña">
	</fieldset>
	
	<span>
	<fieldset class="form-group">
	<input type="hidden" name="registro" value=1>
    <input type="submit" name="submit" class="btn btn-success" value="Registrarse">
	</fieldset>
<div class="checkbox">
	<label>
	<input type="checkbox" value=1 name="permanecerIniciada">Mantener la sesión iniciada
	</label>
</div>
	</span>
	
	<p><a class="alternarFormularios">Acceder a la herramienta</a></p>
	</form>
	
<!-- FORMULARIO DE LOGIN !-->	
	<form method="POST" id="formularioLogin">

	<fieldset class="form-group">
	<label for="user">Usuario</label>
	<input type="text" class="form-control" name="user" id="user" placeholder="Ingresa tu nombre de usuario"> 
	</fieldset>
	
	<fieldset class="form-group">
	<label for="pass">Contraseña</label>
	<input type="password" class="form-control" name="pass" id="pass" placeholder="Ingresa tu contraseña">
	</fieldset>
	
	<fieldset class="form-group">
	<input type="hidden" name="registro" value=0>
	<input type="submit" name="submit" class="btn btn-primary btn-lg" value="Accesar">
	</fieldset>
<div class="checkbox">
	<label>
	<input type="checkbox" value=1 name="permanecerIniciada">Mantener la sesión iniciada
	</label>
</div>

	<p><a class="alternarFormularios">No estoy registrado</a></p>
	</form>

</div>
</div>

	<div id="error">
	<?php 
        if($error!="")
        {
        echo "<div class='alert alert-danger' role='alert'><strong>Failed</strong>".$error."</div>"; 
        }
    
    ?>	
	</div>

<script src="jquery/jquery.min.js"></script>
<script src="jquery/jquery-ui/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
<script src="bootstrap4/js/bootstrap.min.js"></script>
	
<!-- Codigo Javascript/jQuery !-->
<script type="text/javascript">

	/*$("form").submit(function(e){
		var falla="";
		if($("#user").val() == "")
		{
			falla += "<br> El campo usuario es requerido <br>";
		}
		if($("#pass").val() == "")
		{
			falla += "<br> El campo password es requerido <br>";
		}
		if(falla != "")
		{
		$("#mError").html("<div class=\"alert alert-danger\" role=\"alert\"><strong>"+falla+"</strong></div>");
		return false;
		}else{
			return true;
			}
	});*/
	
//metodo para alternar de un formulario a otro
$(".alternarFormularios").click(function(){
	$("#formularioRegistro").toggle();
	$("#formularioLogin").toggle();
});
    

</script>
	
</body>
</html>