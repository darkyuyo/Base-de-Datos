<?php 

include_once "conexion.php";
session_start();

echo "User: ".$_SESSION["admin"]."<br>";
if(isset($_POST["crear"])){
  $album=$_POST['album'];
  $sql="SELECT * FROM albumes WHERE (Nombre=?) AND (ID_A=?)";
  $sentencia=$pdo->prepare($sql);
  $sentencia->execute(array($album,$_SESSION["ID"]));
  $resultado1=$sentencia->fetch();
  if($resultado1){
    echo"Ya tienes un album con ese nombre";
  }
  else{
    $create = 'INSERT INTO albumes (Nombre,Cantidad_canciones,Fecha_salida,ID_A) VALUES (?,0,LOCALTIMESTAMP,?)';
    $agregar = $pdo->prepare($create);
    $agregar->execute(array($album,$_SESSION["ID"]));
    echo"Se creo el album ".$album; 
  }
}
elseif(isset($_POST["borrar"])){
  $album=$_POST['album'];
  $sql="SELECT * FROM albumes WHERE (Nombre=?) AND (ID_A=?)";
  $sentencia=$pdo->prepare($sql);
  $sentencia->execute(array($album,$_SESSION["ID"]));
  $resultado1=$sentencia->fetch();
  if(!$resultado1){
    echo"No tienes ese album";
  }
  else{
    $borrar="DELETE FROM albumes WHERE (Nombre=?) AND (ID_A=?)";
    $sentencia_borrar=$pdo->prepare($borrar);
    $sentencia_borrar->execute(array($album,$_SESSION["ID"]));
    echo"Borraste tu album ".$album;
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
    <title>crear Album</title>
  </head>
  <body>
  <div class="d-flex justify-content-center h-100">
<form method="POST">
  <div class="form-group">
    <label for="exampleInputEmail1">Nombre del album</label>
    <input type="text" name="album">
  </div>
  <div class="row align-items-start" >
  <button type="submit" name="crear" class="btn btn-primary">Crear</button>
  <button type="submit" name="borrar" class="btn btn-primary">Borrar</button>
</form>
<br>
<form action="artista.php" method="POST">
<button type="submit" class="btn btn-primary">Volver</button>
</form>
            
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>