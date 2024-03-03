<?php

include "../utilities/Conect.php";

        $username = isset($_POST['username']) ? $_POST['username'] : "";
        $password = isset($_POST['password']) ? $_POST['password'] : "";

        $obj = new Conect();
        $con = $obj->getCon();
        $stmt = mysqli_prepare($con,"SELECT id,username,nombre_corto,id_tipo_usuario,id_responsable from users where username = ? and pass = ? and activo=1");
        mysqli_stmt_bind_param($stmt,'ss', $username, $password);
        /* ejecuta sentencias preparadas */
        mysqli_stmt_execute($stmt);
        $rs = mysqli_stmt_get_result($stmt);
        $obj->closeCon($con);
        if (mysqli_stmt_affected_rows($stmt)){
            session_start();
            if($data  = mysqli_fetch_row($rs)){
                $_SESSION["id"] = $data[0];
                $_SESSION["username"] = $data[1];
                $_SESSION['nombre_corto'] = $data[2];
                $_SESSION['id_tipo_usuario'] = $data[3];
                $_SESSION["id_responsable"] = $data[4];
            }else{
                echo "error";
            }
            
            //header("Location: Vistas/dashboard.php");
            echo "autenticado";        
        }else{
            echo "error";
        }
?>