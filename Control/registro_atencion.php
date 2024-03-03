<?php
include_once "../utilities/Conect.php";
$obj = new Conect();
$con = $obj->getCon();
$id_mantenimiento = isset($_POST['id_mantenimiento'])? $_POST['id_mantenimiento'] : "";
$proveedor = isset($_POST['proveedor'])? $_POST['proveedor'] : "";

$proveedor = ($proveedor=="si"?1:0);

$idContrato = isset($_POST['idContrato'])? $_POST['idContrato'] : "";

$material = isset($_POST['material'])? $_POST['material'] : "";
$fechaInicio = isset($_POST['fechaInicio'])? $_POST['fechaInicio'] : "";
$fechaTermino = isset($_POST['fechaTermino'])? $_POST['fechaTermino'] : "";

$actividad = isset($_POST['actividad'])? $_POST['actividad'] : "";
$observaciones = isset($_POST['observaciones'])? $_POST['observaciones'] : "";


$idRegistro = isset($_POST['idRegistroOculto'])? $_POST['idRegistroOculto'] : "";

$estatus = 6;//Estatus para ATENDIDO

$stmt = mysqli_prepare($con,"INSERT INTO atencion (id_tipo_mantenimiento, proveedor, id_contrato, material, fecha_inicio, fecha_fin,desc_actividad,observaciones,id_registro) 
values(?,?,?,?,?,?,?,?,?);");
mysqli_stmt_bind_param($stmt,'iiisssssi', $id_mantenimiento,$proveedor, $idContrato,$material,$fechaInicio,$fechaTermino,$actividad,$observaciones,$idRegistro);

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