<?php
include_once "conexion.php";

session_start();

if(isset($_SESSION["admin"])){
  echo "User: ".$_SESSION["admin"];
  echo"<br>";
}
else{
  echo "No iniciaste sesion";
}
if(isset($_POST["seguir"])){
  $user=$_POST['usuario'];
  $sql="SELECT * FROM personas WHERE Usuario = ?";
  $sentencia=$pdo->prepare($sql);
  $sentencia->execute(array($user));
  $resultado=$sentencia->fetch();
  if(!$resultado){
    echo"No existe el usuario";
  }
  else{
    $sql_siguen="SELECT * FROM siguen3 WHERE (ID_1=$_SESSION[ID_P]) AND (ID_2=$resultado[ID_P])";
    $sentencia=$pdo->prepare($sql_siguen);
    $sentencia->execute();
    $resultado1=$sentencia->fetch();
    if($resultado1){
      echo"Ya sigue a este usuario";
    }
    elseif($resultado["ID_P"]==$_SESSION["ID_P"]){
      echo"No te puedes seguir a ti mismo";
    }
    else{
      $create = 'INSERT INTO siguen3 (ID_1,ID_2) VALUES (?,?)';
      $agregar = $pdo->prepare($create);
      $agregar->execute(array($_SESSION["ID_P"],$resultado["ID_P"]));
      echo"Siguiendo a ".$resultado["Usuario"]; 
    }
  }
}
elseif(isset($_POST["borrar"])){
  $user=$_POST['usuario'];
  $sql="SELECT * FROM personas WHERE Usuario = ?";
  $sentencia=$pdo->prepare($sql);
  $sentencia->execute(array($user));
  $resultado=$sentencia->fetch();
  if(!$resultado){
    echo"No existe el usuario";
  }
  else{
    $sql_siguen="SELECT * FROM siguen3 WHERE (ID_1=$_SESSION[ID_P]) AND (ID_2=$resultado[ID_P])";
    $sentencia=$pdo->prepare($sql_siguen);
    $sentencia->execute();
    $resultado1=$sentencia->fetch();
    if(!$resultado1){
      echo"No sigues a este usuario";
    }
    else{
      $borrar="DELETE FROM siguen3 WHERE (ID_1=?) AND (ID_2=?)";
      $sentencia_borrar=$pdo->prepare($borrar);
      $sentencia_borrar->execute(array($_SESSION["ID_P"],$resultado["ID_P"]));
      echo"Ya no sigues a ",$resultado["Usuario"];
    }
  }
}

?>
<!doctype html>
<html lang="en">
  <head>
    	<!--Bootsrap 4 CDN-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
    <!--Fontawesome CDN-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

	<!--Custom styles-->
	<link rel="stylesheet" type="text/css" href="styles.css">
    <title>Seguir personas</title>
  </head>
  <body>
  <div class="d-flex justify-content-center h-100">
<form method="POST">
  <div class="form-group">
    <label for="exampleInputEmail1">Usuario de la persona</label>
    <input type="text" name="usuario">
  </div>
  <div class="row align-items-start" >
  <button type="submit" name="seguir" class="btn btn-primary">Seguir</button>
  <button type="submit" name="borrar" class="btn btn-primary">Dejar de seguir</button>
</form>
<br>
<form action="usuario.php" method="POST">
<button type="submit" class="btn btn-primary">Volver</button>
</form>
            
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>