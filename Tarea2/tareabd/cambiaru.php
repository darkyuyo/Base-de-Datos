<?php
session_start();
include_once "conexion.php";
echo "User: ".$_SESSION["admin"];
echo"<br>"; 
if($_POST){
    $user=$_POST['usuario'];
    $pw=$_POST['pw'];
    $mail=$_POST['mail'];
    $tel=$_POST['tel'];
    $nombre=$_POST['nombre'];
    $apellido=$_POST['apellido'];
    $sql="SELECT * FROM personas WHERE (Usuario = ?)";
    $sentencia=$pdo->prepare($sql);
    $sentencia->execute(array($user));
    $resultado=$sentencia->fetch();
    if($resultado){
        echo"Ya existe ese usuario";
    }
    else{
        $sql_u="UPDATE personas SET Usuario=?,Contrasena=?,Mail=?,Telefono=?,Nombre=?,Apellido=? WHERE ID_P=?";
        $sentencia_u=$pdo->prepare($sql_u);
        $sentencia_u->execute(array($user,$pw,$mail,$tel,$nombre,$apellido,$_SESSION["ID_P"]));
        $_SESSION["admin"]=$user; 
        header("location:cambiaru.php");

    }
}
?>
<!doctype html>
<html lang="en">
  <head>
  <title>Modificar cuenta </title>
	<!--Bootsrap 4 CDN-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
    <!--Fontawesome CDN-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

	<!--Custom styles-->
	<link rel="stylesheet" type="text/css" href="styles.css">
  </head>
  <body>
  <div class="d-flex justify-content-center h-100">
  <form method="POST">
  <div class="form-group">
    <label for="exampleInputEmail1">Usuario</label>
    <input type="text" name="usuario">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Contrasena</label>
    <input type="text" name="pw">   
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Mail</label>
    <input type="text" name="mail">   
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Telefóno</label>
    <input type="text" name="tel">   
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Nombre</label>
    <input type="text" name="nombre">   
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Apellido</label>
    <input type="text" name="apellido">   
  </div>
  <div class="row align-items-start" >
  <button type="submit" class="btn btn-primary">Cambiar</button>
</form>
<form action="usuario.php" method="POST">
<button type="submit" class="btn btn-primary">Volver</button>
</form>
  </body>
</html>