<?php

$link='mysql:host=localhost;dbname=tarea2';
$user='root';
$pw='';

try{
    $pdo= new PDO($link,$user,$pw);
}catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>