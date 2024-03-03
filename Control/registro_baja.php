<?php
include_once "../utilities/Conect.php";
$obj = new Conect();
$con = $obj->getCon();

$observaciones = isset($_POST['observacionesB'])? $_POST['observacionesB'] : "";

$idRegistro = isset($_POST['idRegistroOcultoB'])? $_POST['idRegistroOcultoB'] : "";

$estatus = 7;//Estatus para BAJA

$stmt = mysqli_prepare($con,"INSERT INTO baja_reporte (observaciones,id_registro) 
values(?,?);");
mysqli_stmt_bind_param($stmt,'si', $observaciones,$idRegistro);

if (mysqli_stmt_execute($stmt)){
    $stmt = mysqli_prepare($con,"UPDATE reporte set estatus = ? where id_reporte = ?");
    mysqli_stmt_bind_param($stmt,'ii', $estatus,$idRegistro);

    if (mysqli_stmt_execute($stmt)){
        $obj->closeCon($con);
        header("Location: ../Vista/dashboard.php");
        exit();
    }else{
        echo "Error en actualizar estatus a reporte: " . $con->error;
    }       
    $obj->closeCon($con);
}else{
    echo "Error en registrar atención: " . $con->error;
}

$obj->closeCon($con);


?>