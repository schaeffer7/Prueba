<?php
if(isset($_POST)){
    //Conexión a la base de datos
    require_once 'includes/conexion.php';
    
    //mysqli_real_escape_string: limpia si vienen datos peligrosos!!!
    $nombre = isset($_POST['nombre'])? mysqli_real_escape_string($db, $_POST['nombre']):false;
    
    // Array de errores
    $errores = array();
    
    //Validar los datos antes de guardar en la base de datos
    // Validar campo nombre
    if (!empty($nombre) && !is_numeric($nombre) && !preg_match("/[0-9]/", $nombre)) {
        $nombre_validado = true;
    } else {
        $nombre_validado = false;
        $errores['nombre']= "El nombre no es válido";
    }    
    
    if(count($errores) == 0){
        $sql = "INSERT INTO categorias VALUES(NULL, '$nombre');";
        $guardar = mysqli_query($db, $sql);
    }
        
}

header('Location: index.php');
