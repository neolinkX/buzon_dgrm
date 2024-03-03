<?php
include_once "../utilities/Conect.php";

$select = isset($_GET['parametro'])? $_GET['parametro'] : "";
$obj = new Conect();
$con = $obj->getCon();
$rs= $obj->doSelect($select,$con);


$opciones = array();

if ($rs->num_rows > 0) {
    while ($row = $rs->fetch_assoc()) {
        $opciones[] = $row;
    }
} else {
    echo "No se encontraron opciones.";
} 

// Enviar la respuesta como JSON
header('Content-Type: application/json;charset=utf-8');
echo json_encode($opciones,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

?>
