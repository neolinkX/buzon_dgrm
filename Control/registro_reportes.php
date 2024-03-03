<?php
include_once "../utilities/Conect.php";
$obj = new Conect();
$con = $obj->getCon();

$tipo_ur = isset($_POST['tipo_ur'])? $_POST['tipo_ur'] : "";
$ur_sol = isset($_POST['ur_sol'])? $_POST['ur_sol'] : "";
$nombre_sol = isset($_POST['nombre_sol'])? $_POST['nombre_sol'] : "";
$correo_sol = isset($_POST['correo_sol'])? $_POST['correo_sol'] : "";
$telefono = isset($_POST['telefono'])? $_POST['telefono'] : "";
$cisco = isset($_POST['cisco'])? $_POST['cisco'] : "";
$id_sede = isset($_POST['id_sede'])? $_POST['id_sede'] : "";
$detalle_ubicacion_sol = isset($_POST['detalle_ubicacion_sol'])? $_POST['detalle_ubicacion_sol'] : "";
$id_tipo_servicio = isset($_POST['id_tipo_servicio'])? $_POST['id_tipo_servicio'] : "";
$desc_reporte = isset($_POST['desc_reporte'])? $_POST['desc_reporte'] : "";
$estatus = 4;//Estatus para NO ATENDIDO AÚN
$imagen_binaria='';
$tipo_foto ="";
if ($_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
    // Validar que el archivo es una imagen
    $permitidos = array("image/jpg", "image/jpeg", "image/png", "image/gif");
    if (!in_array($_FILES['imagen']['type'], $permitidos)) {
        session_start();
        $_SESSION['error_message'] = 'El archivo no es una imagen válida. Por favor, selecciona una imagen JPG o PNG.';
        header("Location: ../Vista/default.php");
        die('El archivo no es una imagen válida. Por favor, selecciona una imagen.');
    }

    if ($_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
        session_start();
        $_SESSION['error_message'] = 'El archivo cargado tiene un error, favor de corregir e intentar nuevamente. Recuerda que la aplicación solo acepta cargar archivo de tipo imagen.';
        header("Location: ../Vista/default.php");
        die('Error durante la carga del archivo: ' . $_FILES['imagen']['error']);
    }
    
    $nombre_foto = $_FILES['imagen']['name'];
    $tipo_foto = $_FILES['imagen']['type'];
    $tamano_foto = $_FILES['imagen']['size'];
    $imagen_temporal = $_FILES['imagen']['tmp_name'];

    // Leer la imagen en formato binario
    $imagen_binaria = file_get_contents($imagen_temporal);
} else {
    // No se envió un archivo de imagen
    $nombre_foto = null;
    $tipo_foto = null;
    $tamano_foto = null;
    $imagen_binaria = null;
}

$stmt = null;

if($tipo_ur == "ESTADOS"){
    $estatus = 5;// Turnado al mismo estado
    $sql = "SELECT id_responsable FROM relacion_responsable_sede WHERE id_sede=?";
    $stmt1 = mysqli_prepare($con,$sql);
    mysqli_stmt_bind_param($stmt1,'i', $id_sede);
    /* ejecuta sentencias preparadas */
    mysqli_stmt_execute($stmt1);
    $rs = mysqli_stmt_get_result($stmt1);
    $data  = $rs->fetch_array(MYSQLI_ASSOC);
    if($data !== null){
    $idEdoResponsable= $data["id_responsable"];


    $stmt = mysqli_prepare($con,"INSERT INTO reporte (ur_sol,nombre_sol,id_sede,detalle_ubicacion_sol,telefono_sol,id_tipo_servicio,desc_reporte,cisco,correo_sol,estatus,file_image,type_image,id_turnado) 
    values(?,?,?,?,?,?,?,?,?,?,?,?,?);");
    mysqli_stmt_bind_param($stmt,'ssississsissi', $ur_sol, $nombre_sol,$id_sede,$detalle_ubicacion_sol,$telefono,$id_tipo_servicio,$desc_reporte,$cisco,$correo_sol,$estatus,$imagen_binaria,$tipo_foto,$idEdoResponsable);
    }
}else{
    $stmt = mysqli_prepare($con,"INSERT INTO reporte (ur_sol,nombre_sol,id_sede,detalle_ubicacion_sol,telefono_sol,id_tipo_servicio,desc_reporte,cisco,correo_sol,estatus,file_image,type_image) 
values(?,?,?,?,?,?,?,?,?,?,?,?);");
mysqli_stmt_bind_param($stmt,'ssississsiss', $ur_sol, $nombre_sol,$id_sede,$detalle_ubicacion_sol,$telefono,$id_tipo_servicio,$desc_reporte,$cisco,$correo_sol,$estatus,$imagen_binaria,$tipo_foto);

}

if (mysqli_stmt_execute($stmt)){
    $ultimo_id = mysqli_insert_id($con);
    $mes_actual = date('m'); 
    $anio_actual = date('Y'); 

    $folio = sprintf("%05d", $ultimo_id).$mes_actual.$anio_actual;


    $stmt = mysqli_prepare($con,"UPDATE reporte set folio = ? where id_reporte = ?");
    mysqli_stmt_bind_param($stmt,'si', $folio,$ultimo_id);
    if (mysqli_stmt_execute($stmt)){
        $obj->closeCon($con);
        header("Location: ../Vista/muestra_folio.php?folio=$folio");
        exit();
    }else{
        echo "Error en generar Folio: " . $con->error;
    }
    $obj->closeCon($con);
}else{
    echo "Error en registrar reporte: " . $con->error;
}

$obj->closeCon($con);


?>