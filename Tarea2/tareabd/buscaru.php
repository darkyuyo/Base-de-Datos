<?php
session_start();
include_once "conexion.php";
echo "User: ".$_SESSION["admin"];
echo"<br>"; 
if(isset($_POST["persona"])){
    $user=$_POST['nombre'];
    $sql="SELECT * FROM personas WHERE Usuario = ?";
    $sentencia=$pdo->prepare($sql);
    $sentencia->execute(array($user));
    $resultado=$sentencia->fetch();
    if(!$resultado){
        echo"No existe el usuario";
    }
    else{
        echo "Usuario: ".$resultado[1]."<br>";
        echo "Mail: ".$resultado[3]."<br>";
        echo "Telefóno: ".$resultado[4]."<br>";
        echo "Nombre: ".$resultado[5]."<br>";
        echo "Apellido: ".$resultado[6]."<br>";
        echo "Seguidores: ".$resultado[7]."<br>";
        echo "Lista de seguidos:<br>";
        $sql_s3="SELECT * FROM siguen3 WHERE ID_1 = ?";
        $sentencia=$pdo->prepare($sql_s3);
        $sentencia->execute(array($resultado[0]));
        $resultado_s3=$sentencia->fetchAll();
        foreach ($resultado_s3 as $valor) {
            $sql_persona="SELECT * FROM personas WHERE (ID_P = ?)";
            $sentencia_persona=$pdo->prepare($sql_persona);
            $sentencia_persona->execute(array($valor[1]));
            $resultado_persona=$sentencia_persona->fetch();
            echo "-".$resultado_persona[1]."<br>";
        }
        echo "Playlist que sigue:<br>";
        $sql_pl="SELECT * FROM siguen2 WHERE ID_P=?";
        $sentencia_pl=$pdo->prepare($sql_pl);
        $sentencia_pl->execute(array($resultado["ID_P"]));
        $resultado_pl=$sentencia_pl->fetchAll();
        foreach($resultado_pl as $pl){
          $sql_pl1="SELECT * FROM playlists WHERE (ID_PL = ?)";
          $sentencia_pl1=$pdo->prepare($sql_pl1);
          $sentencia_pl1->execute(array($pl["ID_PL"]));
          $resultado_pl1=$sentencia_pl1->fetch(); 
          echo "-".$resultado_pl1["Nombre"]."<br>";
        }
        $sql_u="SELECT * FROM usuarios WHERE ID_P = ?";
        $sentencia_u=$pdo->prepare($sql_u);
        $sentencia_u->execute(array($resultado["ID_P"]));
        $resultado_u=$sentencia_u->fetch();
        if($resultado_u){
          echo "Canciones que le gustan:<br>";
          $sql_gustan="SELECT * FROM gustan WHERE (ID_U=?)";
          $sentencia_gustan=$pdo->prepare($sql_gustan);
          $sentencia_gustan->execute(array($resultado_u["ID_U"]));
          $resultado_gustan=$sentencia_gustan->fetchAll();
          foreach($resultado_gustan as $gustan){
            $sql_cancion="SELECT * FROM canciones WHERE (ID_C=?)";
            $sentencia_cancion=$pdo->prepare($sql_cancion);
            $sentencia_cancion->execute(array($gustan["ID_C"]));
            $resultado_cancion=$sentencia_cancion->fetch();
            echo"-".$resultado_cancion["Nombre"]."<br>";
          }
          echo "Playlist que ha creado:<br>";
          $sql_creadas="SELECT * FROM playlists WHERE (ID_U=?)";
          $sentencia_creadas=$pdo->prepare($sql_creadas);
          $sentencia_creadas->execute(array($resultado_u["ID_U"]));
          $resultado_creadas=$sentencia_creadas->fetchAll();
          foreach($resultado_creadas as $pl){
            echo"-".$pl["Nombre"]."<br>";
          }
        }
        $sql_a="SELECT * FROM artistas WHERE ID_P = ?";
        $sentencia_a=$pdo->prepare($sql_a);
        $sentencia_a->execute(array($resultado["ID_P"]));
        $resultado_a=$sentencia_a->fetch();
        if($resultado_a){
          echo"Albumes que ha creado:<br>";
          $sql_albumes="SELECT * FROM albumes WHERE (ID_A=?)";
          $sentencia_albumes=$pdo->prepare($sql_albumes);
          $sentencia_albumes->execute(array($resultado_a["ID_A"]));
          $resultado_albumes=$sentencia_albumes->fetchAll();
          foreach($resultado_albumes as $album){
            echo"-".$album["Nombre"]."<br>";
          }
          echo"Canciones que ha creado:<br>";
          $sql_canciones="SELECT * FROM canciones WHERE (ID_A=?)";
          $sentencia_canciones=$pdo->prepare($sql_canciones);
          $sentencia_canciones->execute(array($resultado_a["ID_A"]));
          $resultado_canciones=$sentencia_canciones->fetchAll();
          foreach($resultado_canciones as $cancion){
            echo"-".$cancion["Nombre"]."<br>";
          }
        }
    }
}
elseif(isset($_POST["cancion"])){
  $cancion=$_POST['nombre'];
  $sql="SELECT * FROM canciones WHERE Nombre = ?";
  $sentencia=$pdo->prepare($sql);
  $sentencia->execute(array($cancion));
  $resultado=$sentencia->fetchAll();
  if(!$resultado){
    echo"No existe esa canción";
  }
  else{
    foreach($resultado as $song){
      $sql_a="SELECT * FROM artistas WHERE ID_A = ?";
      $sentencia_a=$pdo->prepare($sql_a);
      $sentencia_a->execute(array($song["ID_A"]));
      $resultado_a=$sentencia_a->fetch();
      $sql_p="SELECT * FROM personas WHERE ID_P = ?";
      $sentencia_p=$pdo->prepare($sql_p);
      $sentencia_p->execute(array($resultado_a["ID_P"]));
      $resultado_p=$sentencia_p->fetch();
      echo"Artista: ".$resultado_p["Usuario"]."<br>";
      if($song["ID_AL"]){
        $sql_al="SELECT * FROM albumes WHERE ID_AL= ?";
        $sentencia_al=$pdo->prepare($sql_al);
        $sentencia_al->execute(array($song["ID_AL"]));
        $resultado_al=$sentencia_al->fetch();
        echo"Album: ".$resultado_al["Nombre"]."<br>";
      }
      else{
        echo"Album: No tiene<br>";
      }
      echo"Nombre: ".$song["Nombre"]."<br>";
      echo"Duración: ".$song["Duración"]."<br>";
      echo"Likes: ".$song["Likes"]."<br>";
      echo"Fecha de salida: ".$song["Fecha_salida"]."<br>";
      echo"------------------";
    }
  }  
}     
elseif(isset($_POST["album"])){
  $album=$_POST['nombre'];
  $sql="SELECT * FROM albumes WHERE Nombre = ?";
  $sentencia=$pdo->prepare($sql);
  $sentencia->execute(array($album));
  $resultado=$sentencia->fetchAll();
  foreach ($resultado as $album2){
    $sql_a="SELECT * FROM artistas WHERE ID_A = ?";
    $sentencia_a=$pdo->prepare($sql_a);
    $sentencia_a->execute(array($album2["ID_A"]));
    $resultado_a=$sentencia_a->fetch();
    $sql_p="SELECT * FROM personas WHERE ID_P = ?";
    $sentencia_p=$pdo->prepare($sql_p);
    $sentencia_p->execute(array($resultado_a["ID_P"]));
    $resultado_p=$sentencia_p->fetch();
    echo"Artista: ".$resultado_p["Usuario"]."<br>";
    $sql_c="SELECT * FROM canciones WHERE ID_AL = ?";
    $sentencia_c=$pdo->prepare($sql_c);
    $sentencia_c->execute(array($album2["ID_AL"]));
    $resultado_c=$sentencia_c->fetchAll();
    echo"Canciones del Album:<br>";
    foreach($resultado_c as $song){
      echo"-".$song["Nombre"]."<br>";
    }
    echo"Fecha Salida: ".$album2["Fecha_salida"]."<br>";
    echo"-------------------";   
  }

}
elseif(isset($_POST["playlist"])){
  $pl=$_POST['nombre'];
  $sql="SELECT * FROM playlists WHERE Nombre = ?";
  $sentencia=$pdo->prepare($sql);
  $sentencia->execute(array($pl));
  $resultado=$sentencia->fetch(); 
  if(!$resultado){
    echo"No existe esa playlist";
  }
  else{
    $sql_u="SELECT * FROM usuarios WHERE ID_U = ?";
    $sentencia_u=$pdo->prepare($sql_u);
    $sentencia_u->execute(array($resultado["ID_U"]));
    $resultado_u=$sentencia_u->fetch();
    $sql_p="SELECT * FROM personas WHERE ID_P=?";
    $sentencia_p=$pdo->prepare($sql_p);
    $sentencia_p->execute(array($resultado_u["ID_P"]));
    $resultado_p=$sentencia_p->fetch();
    echo"Creador: ".$resultado_p["Usuario"]."<br>";
    echo"Nombre: ".$resultado["Nombre"]."<br>";
    echo"Seguidores: ".$resultado["Cantidad_seguidores"]."<br>";
    echo"Fecha creación: ".$resultado["Fecha_creacion"]."<br>";
    echo"Canciones:<br>";
    $sql_co="SELECT * FROM conforman WHERE ID_PL = ?";
    $sentencia_co=$pdo->prepare($sql_co);
    $sentencia_co->execute(array($resultado["ID_PL"]));
    $resultado_co=$sentencia_co->fetchAll();
    foreach($resultado_co as $conforman){ 
      $sql_c="SELECT * FROM canciones WHERE ID_C = ?";
      $sentencia_c=$pdo->prepare($sql_c);
      $sentencia_c->execute(array($conforman["ID_C"]));
      $resultado_c=$sentencia_c->fetch();
      echo"-".$resultado_c["Nombre"]."<br>";
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
    <title>Poyofy uwu</title>
  </head>
  <body>
<div class="d-flex justify-content-center h-100">
<form method="POST">
  <div class="form-group">
    <label for="exampleInputEmail1">Buscar</label>
    <input type="text" name="nombre">
  </div>
  <button type="submit" name="persona" class="btn btn-primary">Persona</button>
  <button type="submit" name="cancion" class="btn btn-primary">Canción</button>
  <button type="submit" name="album" class="btn btn-primary">Album</button>
  <button type="submit" name="playlist" class="btn btn-primary">Playlist</button>
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