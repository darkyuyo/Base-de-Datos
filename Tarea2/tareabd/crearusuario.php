<?php

include_once 'conexion.php';

if($_POST){
  $user=$_POST['usuario'];
  $pw=$_POST['contrasena']; 
  $mail=$_POST['mail'];
  $tel=$_POST['telefono'];
  $name=$_POST['nombre'];
  $last=$_POST['apellido'];
  $sql="SELECT * FROM personas WHERE Usuario = ?";
  $sentencia=$pdo->prepare($sql);
  $sentencia->execute(array($user));
  $resultado=$sentencia->fetch();
  if($resultado){
    echo"Ya existe ese usuario";
  }
  else{
    $create = 'INSERT INTO personas (Usuario,Contrasena,Mail,Telefono,Nombre,Apellido) VALUES (?,?,?,?,?,?)';
    $agregar = $pdo->prepare($create);
    $agregar->execute(array($user,$pw,$mail,$tel,$name,$last));
    $sentencia=$pdo->prepare($sql);
    $sentencia->execute(array($user));
    $resultado=$sentencia->fetch(); 
    $create_usuario='INSERT INTO usuarios (ID_P) VALUES (?)';
    $agregar_usuario= $pdo->prepare($create_usuario);
    $agregar_usuario->execute(array($resultado["ID_P"]));
    header("location:index.php");
  }
}   
?>
<!doctype html>
<html lang="en">
  <head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
    <!--Fontawesome CDN-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

	<!--Custom styles-->
	<link rel="stylesheet" type="text/css" href="styles.css">

    <title>Crear cuenta de Usuario</title>
  </head>
  <body>
  <div class="container">
<div class="d-flex justify-content-center h-100">
<div class="card">  
  <h3>Crear cuenta de usuario</h3>
  <div class="card-body">
  <form method="POST">
  <div class="form-group">
  <div class="input-group-prepend">
		<span class="input-group-text"><i class="fa fa-user-circle"></i></span>
    <input type="text" name="usuario" placeholder="Usuario">
    </div>
    <div class="form-group">
    <div class="input-group-prepend">
		<span class="input-group-text"><i class="fa fa-key"></i></span>
    <input type="text" name="contrasena" placeholder="Contraseña">
    </div>
    <div class="form-group">
    <div class="input-group-prepend">
		<span class="input-group-text"><i class="fa fa-envelope"></i></span>
    <input type="text" name="mail" placeholder="Mail">
    </div>
    <div class="form-group">
    <div class="input-group-prepend">
		<span class="input-group-text"><i class="fa fa-mobile"></i></span>
    <input type="text" name="telefono" placeholder="Telefono">
    </div>
    <div class="form-group">
    <div class="input-group-prepend">
		<span class="input-group-text"><i class="fa fa-address-book"></i></span>
    <input type="text" name="nombre" placeholder="Nombre">
    </div>
    <div class="form-group">
    <div class="input-group-prepend">
		<span class="input-group-text"><i class="fa fa-address-book"></i></span>
    <input type="text" name="apellido"placeholder="Apellido">
    </div>
    <br>
  <button type="submit" class="btn btn-primary">Crear cuenta</button>
</form>
<br>
<br>
<form action="index.php" method="POST">
<button type="submit" class="btn btn-primary">Volver al inicio</button>
</form>

  </body>
</html>