<?php 
session_start();
if(isset($_SESSION["admin"])){
    echo "Bienvenido Artista ".$_SESSION["admin"];
}
else{
    echo "No iniciaste sesion";
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
    <title>Cuenta usuario</title>
  </head>
<body>
<div class="row align-items-start" >
<form action="buscara.php" method="POST">
<button type="submit" class="btn btn-primary">Buscar</button>
</form>
<form action="crearalbum.php" method="POST">
<button type="submit" class="btn btn-primary">Crear Album</button>
</form>
<form action="crearcancion.php" method="POST">
<button type="submit" class="btn btn-primary">Crear canción</button>
</form>
<form action="seguirpersonasa.php" method="POST">
<button type="submit" class="btn btn-primary">Seguir personas</button>
</form>
<form action="seguirplaylista.php" method="POST">
<button type="submit" class="btn btn-primary">Seguir Playlist</button>
</form>
<form action="cambiara.php" method="POST">
<button type="submit" class="btn btn-primary">Modificar cuenta</button>
</form>
<form action="cerrar.php" method="POST">
<button type="submit" class="btn btn-primary">Cerrar sesión</button>
</form>
<form action="borrarcuenta.php" method="POST">
<button type="submit" class="btn btn-primary">Borrar cuenta</button>
</form>
</div>
<div class="d-flex justify-content-center h-100">
<img src="imagenes/kirbylofi.jpg" width="600  " height="300">
  </body>
</html>
