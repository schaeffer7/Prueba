<?php

if (isset($_POST)) {
    //Conexión a al base de datos
    require_once 'includes/conexion.php';

    // Recoger los valores del formulario de formulario
    $nombre = isset($_POST['nombre']) ? mysqli_real_escape_string($db, $_POST['nombre']) : false;
    $apellidos = isset($_POST['apellidos']) ? mysqli_real_escape_string($db, $_POST['apellidos']) : false;
    $email = isset($_POST['email']) ? mysqli_real_escape_string($db, trim($_POST['email'])) : false;

    // Array de errores
    $errores = array();

    //Validar los datos antes de guardar en la base de datos
    // Validar campo nombre
    if (!empty($nombre) && !is_numeric($nombre) && !preg_match("/[0-9]/", $nombre)) {
        $nombre_validado = true;
    } else {
        $nombre_validado = false;
        $errores['nombre'] = "El nombre no es válido";
    }

    // Validar campo apellidos
    if (!empty($apellidos) && !is_numeric($apellidos) && !preg_match("/[0-9]/", $apellidos)) {
        $nombre_validado = true;
    } else {
        $apellidos_validado = false;
        $errores['apellidos'] = "Los apellidos no son válidos";
    }

    // Validar el email
    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_validado = true;
    } else {
        $email_validado = false;
        $errores['email'] = "El email no es válido";
    }

    $guardar_usuario = false;

    if (count($errores) == 0) {
        $usuario = $_SESSION['usuario'];
        $guardar_usuario = true;

        // COMPROBAR SI EL EMAIL YA EXISTE
        $sql = "SELECT id, email FROM usuarios WHERE email = '$email'";
        $isset_email = mysqli_query($db, $sql);
        $isset_user = mysqli_fetch_assoc($isset_email);

        //Valido si el usuario que estoy actualizando no existe en la BD,
        // o el valor coincide con el que estoy identificado...
        if ($isset_user['id'] == $usuario['id'] || empty($isset_user)) {

            // ACTUALIZAR EL USUARIO EN LA TABLA USUARIOS DE LA DB
            $sql = "UPDATE usuarios SET " .
                    "nombre = '$nombre', " .
                    "apellidos = '$apellidos', " .
                    "email = '$email' " .
                    "WHERE id = " . $usuario['id'];
            $guardar = mysqli_query($db, $sql);

            if ($guardar) {
                //actualizo las variables de sesión...
                $_SESSION['usuario']['nombre'] = $nombre;
                $_SESSION['usuario']['apellidos'] = $apellidos;
                $_SESSION['usuario']['email'] = $email;

                $_SESSION['completado'] = "Tus datos se han actualizado con éxito";
            } else {
                $_SESSION['errores']['general'] = "Falló al guardar la actualización de tus datos!!!";
            }
        } else {
            $_SESSION['errores']['general'] = "El usuario ya existe!!";
        }
        
    } else {
        $_SESSION['errores'] = $errores;
    }
}//if isset $_POST

header('Location: mis-datos.php');
  