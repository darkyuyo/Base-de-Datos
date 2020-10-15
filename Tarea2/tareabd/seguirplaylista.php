<?php
include_once "conexion.php";

session_start();

echo "User: ".$_SESSION["admin"];
echo"<br>";

if(isset($_POST["seguir"])){
    $pl=$_POST['pl'];
    $sql="SELECT * FROM playlists WHERE Nombre = ?";
    $sentencia=$pdo->prepare($sql);
    $sentencia->execute(array($pl));
    $resultado=$sentencia->fetch();
    if(!$resultado){
        echo"No existe esa playlist";
    }
    else{
        $sql_1="SELECT * FROM siguen2 WHERE (ID_P=?) AND (ID_PL=?)";
        $sentencia_1=$pdo->prepare($sql_1);
        $sentencia_1->execute(array($_SESSION["ID_P"],$resultado["ID_PL"]));
        $resultado_1=$sentencia_1->fetch();
        if($resultado_1){
            echo"Ya sigues esta playlist";
        }
        else{
            $create = 'INSERT INTO siguen2 (ID_P,ID_PL) VALUES (?,?)';
            $agregar = $pdo->prepare($create);
            $agregar->execute(array($_SESSION["ID_P"],$resultado["ID_PL"]));
            echo"Sigues a la playlist ".$pl;
        }
    }
}
elseif(isset($_POST["borrar"])){
  $pl=$_POST['pl'];
  $sql="SELECT * FROM playlists WHERE Nombre = ?";
  $sentencia=$pdo->prepare($sql);
  $sentencia->execute(array($pl));
  $resultado=$sentencia->fetch();
  if(!$resultado){
      echo"No existe esa playlist";
  }
  else{
    $sql_1="SELECT * FROM siguen2 WHERE (ID_P=?) AND (ID_PL=?)";
    $sentencia_1=$pdo->prepare($sql_1);
    $sentencia_1->execute(array($_SESSION["ID_P"],$resultado["ID_PL"]));
    $resultado_1=$sentencia_1->fetch();
    if(!$resultado_1){
        echo"No sigues esta playlist";
    }
    else{
      $borrar="DELETE FROM siguen2 WHERE (ID_P=?) AND (ID_PL=?)";
      $sentencia_borrar=$pdo->prepare($borrar);
      $sentencia_borrar->execute(array($_SESSION["ID_P"],$resultado["ID_PL"]));
      echo"Ya no sigues a la playlist ",$pl;
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
    <title>Seguir Playlist</title>
  </head>
  <body>
  <div class="d-flex justify-content-center h-100">
<form method="POST">
  <div class="form-group">
    <label for="exampleInputEmail1">Nombre de la playlist</label>
    <input type="text" name="pl">
  </div>
  <div class="row align-items-start" >
  <button type="submit" name="seguir" class="btn btn-primary">Seguir</button>
  <button type="submit" name="borrar" class="btn btn-primary">Dejar de seguir</button>
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