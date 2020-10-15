<?php
include_once "conexion.php";
session_start();
echo "User: ".$_SESSION["admin"];
echo"<br>";
if(isset($_POST["crear"])){
  $album=$_POST['album'];
  $nombre=$_POST['nombre'];
  $duracion=$_POST['duracion'];
  $sql="SELECT * FROM canciones WHERE (Nombre = ?) AND (ID_A = ?)";
  $sentencia=$pdo->prepare($sql);
  $sentencia->execute(array($nombre,$_SESSION["ID"]));
  $resultado=$sentencia->fetch();
  if($resultado){
    echo"Ya tienes esta cancion";
  }
  elseif($album==""){
    $create_s = 'INSERT INTO canciones (Nombre,Likes,Duración,Fecha_Salida,ID_A) VALUES (?,0,?,LOCALTIMESTAMP,?)';
    $agregar_s = $pdo->prepare($create_s);
    $agregar_s->execute(array($nombre,$duracion,$_SESSION["ID"]));
    echo"Agregado sin album";
  }
  else{
    $sql_album="SELECT * FROM albumes WHERE (Nombre = ?) AND (ID_A = ?)";
    $sentencia_album=$pdo->prepare($sql_album);
    $sentencia_album->execute(array($album,$_SESSION["ID"]));
    $resultado_album=$sentencia_album->fetch();
    if($resultado_album){
      $create = 'INSERT INTO canciones (Nombre,Likes,Duración,Fecha_Salida,ID_AL,ID_A) VALUES (?,0,?,LOCALTIMESTAMP,?,?)';
      $agregar = $pdo->prepare($create);
      $agregar->execute(array($nombre,$duracion,$resultado_album["ID_AL"],$_SESSION["ID"]));
      echo"Agregado";
    }
    else{
      echo"No existe ese album";
    }
  }
}
elseif(isset($_POST["borrar"])){
  $album=$_POST['album'];
  $nombre=$_POST['nombre'];
  $duracion=$_POST['duracion'];
  $sql="SELECT * FROM canciones WHERE (Nombre = ?) AND (ID_A = ?)";
  $sentencia=$pdo->prepare($sql);
  $sentencia->execute(array($nombre,$_SESSION["ID"]));
  $resultado=$sentencia->fetch();
  if(!$resultado){
    echo"No tienes esa canción";
  }
  else{
    $borrar="DELETE FROM canciones WHERE (Nombre = ?) AND (ID_A = ?)";
    $sentencia_borrar=$pdo->prepare($borrar);
    $sentencia_borrar->execute(array($nombre,$_SESSION["ID"]));
    echo"Borraste tu cancion ",$nombre;
  }
}
if(isset($_POST["sacar"])){
  $album=$_POST['album'];
  $nombre=$_POST['nombre'];
  $duracion=$_POST['duracion'];
  $sql="SELECT * FROM canciones WHERE (Nombre = ?) AND (ID_A = ?)";
  $sentencia=$pdo->prepare($sql);
  $sentencia->execute(array($nombre,$_SESSION["ID"]));
  $resultado=$sentencia->fetch();
  if(!$resultado){
    echo"No tienes esa canción";  
  }
  else{
    $sql_u="UPDATE canciones SET ID_AL=? WHERE ID_C=?";
    $sentencia_u=$pdo->prepare($sql_u);
    $sentencia_u->execute(array(NULL,$resultado["ID_C"]));
    echo $nombre." ya no pertenece a ningún album";
  }
}
if(isset($_POST["agregar"])){
  $album=$_POST['album'];
  $nombre=$_POST['nombre'];
  $duracion=$_POST['duracion'];
  $sql="SELECT * FROM canciones WHERE (Nombre = ?) AND (ID_A = ?)";
  $sentencia=$pdo->prepare($sql);
  $sentencia->execute(array($nombre,$_SESSION["ID"]));
  $resultado=$sentencia->fetch();
  if(!$resultado){
    echo"No tienes esa canción";
  }
  else{
    $sql_album="SELECT * FROM albumes WHERE (Nombre = ?) AND (ID_A = ?)";
    $sentencia_album=$pdo->prepare($sql_album);
    $sentencia_album->execute(array($album,$_SESSION["ID"]));
    $resultado_album=$sentencia_album->fetch();
    if(!$resultado_album){
      echo"No tienes ese album";
    }
    else{
      $sql_u="UPDATE canciones SET ID_AL=? WHERE ID_C=?";
      $sentencia_u=$pdo->prepare($sql_u);
      $sentencia_u->execute(array($resultado_album["ID_AL"],$resultado["ID_C"]));
      echo $nombre." pertenece al album ".$album;
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
    <title>Crear canción</title>
  </head>
  <body>
  <div class="d-flex justify-content-center h-100">
  <form method="POST">
  <div class="form-group">
    <label for="exampleInputEmail1">Nombre del album</label>
    <input type="text" name="album">
    <label for="exampleInputPassword1">(Dejar en blanco si no es de un album)</label>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Nombre de la canción</label>
    <input type="text" name="nombre">   
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Duración</label>
    <input type="text" name="duracion">   
  </div>
  <div class="row align-items-start" >
  <button type="submit" name="crear" class="btn btn-primary">Crear canción</button>
  <button type="submit" name="borrar" class="btn btn-primary">Borrar canción</button>
  <button type="submit" name="agregar" class="btn btn-primary">Agregar a album</button>
  <button type="submit" name="sacar" class="btn btn-primary">Sacar de album</button>
</form>
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