<?php
include_once "conexion.php";

session_start();

echo "User: ".$_SESSION["admin"];
echo"<br>";
if(isset($_POST["crear"])){
    $pl=$_POST["playlist"];
    $cancion=$_POST["cancion"];
    $artista=$_POST["artista"];
    $sql="SELECT * FROM playlists WHERE (Nombre=?) AND (ID_U=?)";
    $sentencia=$pdo->prepare($sql);
    $sentencia->execute(array($pl,$_SESSION["ID"]));
    $resultado=$sentencia->fetch();
    if(!$resultado){
        echo"No has creado la playlist";
    }
    else{
        $sql_c="SELECT * FROM personas WHERE (Usuario=?)";
        $sentencia_c=$pdo->prepare($sql_c);
        $sentencia_c->execute(array($artista));
        $resultado_c=$sentencia_c->fetch();
        if(!$resultado_c){
            echo"No existe ese usuario";
        }
        else{
            $sql_2="SELECT * FROM artistas WHERE (ID_P=?)";
            $sentencia_2=$pdo->prepare($sql_2);
            $sentencia_2->execute(array($resultado_c["ID_P"]));
            $resultado_2=$sentencia_2->fetch();
            if(!$resultado_2){
                echo"No existe ese artista";
            }
            else{
                $sql_3="SELECT * FROM canciones WHERE (Nombre=?) AND (ID_A=?)";
                $sentencia_3=$pdo->prepare($sql_3);
                $sentencia_3->execute(array($cancion,$resultado_2["ID_A"]));
                $resultado_3=$sentencia_3->fetch();
                if(!$resultado_3){
                    echo"El artista no tiene esa canción";
                }
                else{
                    $sql_4="SELECT * FROM conforman WHERE (ID_C=?) AND (ID_PL=?)";
                    $sentencia_4=$pdo->prepare($sql_4);
                    $sentencia_4->execute(array($resultado_3["ID_C"],$resultado["ID_PL"]));
                    $resultado_4=$sentencia_4->fetch();
                    if(!$resultado_4){
                        $create = 'INSERT INTO conforman (ID_C,ID_PL) VALUES (?,?)';
                        $agregar = $pdo->prepare($create);
                        $agregar->execute(array($resultado_3["ID_C"],$resultado["ID_PL"]));  
                        echo"Se añadio la cancion ".$cancion." a la playlist".$pl;
                    }
                    else{
                        echo"La playlist ya tiene esta canción";
                    }
                }
            }
        }
    }
}
elseif(isset($_POST["borrar"])){
  $pl=$_POST["playlist"];
  $cancion=$_POST["cancion"];
  $artista=$_POST["artista"];
  $sql="SELECT * FROM playlists WHERE (Nombre=?) AND (ID_U=?)";
  $sentencia=$pdo->prepare($sql);
  $sentencia->execute(array($pl,$_SESSION["ID"]));
  $resultado=$sentencia->fetch();
  if(!$resultado){
      echo"No has creado la playlist";
  }
  else{
      $sql_c="SELECT * FROM personas WHERE (Usuario=?)";
      $sentencia_c=$pdo->prepare($sql_c);
      $sentencia_c->execute(array($artista));
      $resultado_c=$sentencia_c->fetch();
      if(!$resultado_c){
          echo"No existe ese usuario";
      }
      else{
          $sql_2="SELECT * FROM artistas WHERE (ID_P=?)";
          $sentencia_2=$pdo->prepare($sql_2);
          $sentencia_2->execute(array($resultado_c["ID_P"]));
          $resultado_2=$sentencia_2->fetch();
          if(!$resultado_2){
              echo"No existe ese artista";
          }
          else{
              $sql_3="SELECT * FROM canciones WHERE (Nombre=?) AND (ID_A=?)";
              $sentencia_3=$pdo->prepare($sql_3);
              $sentencia_3->execute(array($cancion,$resultado_2["ID_A"]));
              $resultado_3=$sentencia_3->fetch();
              if(!$resultado_3){
                  echo"El artista no tiene esa canción";
              }
              else{
                  $sql_4="SELECT * FROM conforman WHERE (ID_C=?) AND (ID_PL=?)";
                  $sentencia_4=$pdo->prepare($sql_4);
                  $sentencia_4->execute(array($resultado_3["ID_C"],$resultado["ID_PL"]));
                  $resultado_4=$sentencia_4->fetch();
                  if(!$resultado_4){
                    echo"No tienes esa cancion en tu playlist";
                  }
                  else{
                    $borrar="DELETE FROM conforman WHERE (ID_C=?) AND (ID_PL=?)";
                    $sentencia_borrar=$pdo->prepare($borrar);
                    $sentencia_borrar->execute(array($resultado_3["ID_C"],$resultado["ID_PL"]));
                    echo"Sacaste la cancion ".$cancion."de la playlist ".$pl;
                  }
              }
          }
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
    <title>Añadir canción </title>
  </head>
  <body>
  <div class="d-flex justify-content-center h-100">
<form method="POST">
  <div class="form-group">
    <label for="exampleInputEmail1">Nombre de la playlist</label>
    <input type="text" name="playlist">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Nombre de la canción</label>
    <input type="text" name="cancion">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Nombre del artista</label>
    <input type="text" name="artista">
  </div>
  <div class="row align-items-start">
  <button type="submit" name="crear" class="btn btn-primary">Crear</button>
  <button type="submit" name="borrar" class="btn btn-primary">Borrar</button>
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