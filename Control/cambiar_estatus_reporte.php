<?php
include_once "../utilities/Conect.php";
$obj = new Conect();
$con = $obj->getCon();

$id_turnado = isset($_POST['id_select'])? $_POST['id_select'] : "";
$id_reporte = isset($_POST['id_reporte'])? $_POST['id_reporte'] : "";
$estatus = isset($_POST['estatus'])? $_POST['estatus'] : "";

$stmt = mysqli_prepare($con,"UPDATE reporte set id_turnado=?,estatus=? where id_reporte=?");
mysqli_stmt_bind_param($stmt,'iii', $id_turnado,$estatus, $id_reporte);

if (mysqli_stmt_execute($stmt)){
    echo "OK";
   $obj->closeCon($con);
}else{
    echo "Error: " . $con->error;
    $obj->closeCon($con);
}
