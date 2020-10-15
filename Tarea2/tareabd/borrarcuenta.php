<?php 
 session_start();
 include_once "conexion.php";
 $sql_borrar="DELETE FROM personas WHERE (ID_P=?)";
 $sentencia=$pdo->prepare($sql_borrar);
 $sentencia->execute(array($_SESSION["ID_P"]));
 header("location:cerrar.php")
 ?>