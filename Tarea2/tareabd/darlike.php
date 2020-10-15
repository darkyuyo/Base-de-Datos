<?php
include_once "conexion.php";

session_start();
echo "User: ".$_SESSION["admin"];
echo"<br>";
if(isset($_POST["seguir"])){
    $nombre=$_POST["nombre"];
    $artista=$_POST["artista"];
    $sql="SELECT * FROM personas WHERE Usuario = ?";
    $sentencia=$pdo->prepare($sql);
    $sentencia->execute(array($artista));
    $resultado=$sentencia->fetch();
    if(!$resultado){
        echo"No existe el usuario";
    }
    else{
        $sql_1="SELECT * FROM artistas WHERE ID_P = ?";
        $sentencia_1=$pdo->prepare($sql_1);
        $sentencia_1->execute(array($resultado["ID_P"]));
        $resultado_1=$sentencia_1->fetch();
        if(!$resultado_1){
            echo"No es artista ese usuario";
        }
        else{
            $sql_2="SELECT * FROM canciones WHERE (Nombre=?) AND (ID_A=?)";
            $sentencia_2=$pdo->prepare($sql_2);
            $sentencia_2->execute(array($nombre,$resultado_1["ID_A"]));
            $resultado_2=$sentencia_2->fetch();
            if(!$resultado_2){
                echo"No tiene esta canción este artista";
            }
            else{
                $sql_3="SELECT * FROM gustan WHERE (ID_C=?) AND (ID_U=?)";
                $sentencia_3=$pdo->prepare($sql_3);
                $sentencia_3->execute(array($resultado_2["ID_C"],$_SESSION["ID"]));
                $resultado_3=$sentencia_3->fetch();
                if($resultado_3){
                    echo"Ya diste like a esta canción";
                }
                else{
                    $create = 'INSERT INTO gustan (ID_C,ID_U) VALUES (?,?)';
                    $agregar = $pdo->prepare($create);
                    $agregar->execute(array($resultado_2["ID_C"],$_SESSION["ID"]));
                    echo"Te gusta ".$nombre;
                }      
            }
        }
    }
}
if(isset($_POST["borrar"])){
  $nombre=$_POST["nombre"];
  $artista=$_POST["artista"];
  $sql="SELECT * FROM personas WHERE Usuario = ?";
  $sentencia=$pdo->prepare($sql);
  $sentencia->execute(array($artista));
  $resultado=$sentencia->fetch();
  if(!$resultado){
      echo"No existe el usuario";
  }
  else{
      $sql_1="SELECT * FROM artistas WHERE ID_P = ?";
      $sentencia_1=$pdo->prepare($sql_1);
      $sentencia_1->execute(array($resultado["ID_P"]));
      $resultado_1=$sentencia_1->fetch();
      if(!$resultado_1){
          echo"No es artista ese usuario";
      }
      else{
          $sql_2="SELECT * FROM canciones WHERE (Nombre=?) AND (ID_A=?)";
          $sentencia_2=$pdo->prepare($sql_2);
          $sentencia_2->execute(array($nombre,$resultado_1["ID_A"]));
          $resultado_2=$sentencia_2->fetch();
          if(!$resultado_2){
              echo"No tiene esta canción este artista";
          }
          else{
              $sql_3="SELECT * FROM gustan WHERE (ID_C=?) AND (ID_U=?)";
              $sentencia_3=$pdo->prepare($sql_3);
              $sentencia_3->execute(array($resultado_2["ID_C"],$_SESSION["ID"]));
              $resultado_3=$sentencia_3->fetch();
              if($resultado_3){
                $borrar="DELETE FROM gustan WHERE (ID_C=?) AND (ID_U=?)";
                $sentencia_borrar=$pdo->prepare($borrar);
                $sentencia_borrar->execute(array($resultado_2["ID_C"],$_SESSION["ID"]));
                echo"Ya no te gusta la cancion ".$nombre;
              }
              else{
                echo"No has dado like a esta cancion";
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
    <title>Dar like</title>
  </head>
<body>
<div class="d-flex justify-content-center h-100">
<form method="POST">
  <div class="form-group">
    <label for="exampleInputEmail1">Nombre de la canción</label>
    <input type="text" name="nombre">
    <br>
    <label for="exampleInputEmail1">Artista de la canción</label>
    <input type="text" name="artista">
  </div>
  <div class="row align-items-start" >
  <button type="submit" name="seguir" class="btn btn-primary">Like</button>
  <button type="submit" name="borrar" class="btn btn-primary">Quitar like</button>
</form>
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