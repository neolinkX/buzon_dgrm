<?php
include "../utilities/Conect.php";
$folio = (isset($_POST['folio'])?$_POST['folio']:"");
$tipo_consulta = (isset($_POST['id_tipo_consulta_folio'])?$_POST['id_tipo_consulta_folio']:"");

        $obj = new Conect();
        $con = $obj->getCon();
        $consulta = "SELECT a.fecha_solicitud,
			a.folio,
			a.nombre_sol,
			a.telefono_sol,
			a.cisco,
			a.correo_sol,
        (SELECT CONCAT(z.id_ur,' - ',z.nombre_ur) FROM cat_ur z WHERE a.ur_sol=z.id_ur) AS UR,
        a.nombre_sol,(SELECT z.nombre_sede from cat_sedes z WHERE z.id_sede=a.id_sede) AS SEDE,
        (SELECT descripcion FROM catalogo z WHERE z.id_cat=a.id_tipo_servicio) AS TIPO_SERVICIO,
        a.detalle_ubicacion_sol,
        a.desc_reporte,
        a.file_image,
        a.type_image,
        (SELECT descripcion FROM catalogo z WHERE z.id_cat=a.id_tipo_servicio)
        FROM reporte a
        WHERE a.folio=?";
        $stmt = mysqli_prepare($con,$consulta);
        mysqli_stmt_bind_param($stmt,'s', $folio);
        /* ejecuta sentencias preparadas */
        mysqli_stmt_execute($stmt);
        $rs = mysqli_stmt_get_result($stmt);
        $obj->closeCon($con);

        $data  = $rs->fetch_array(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/estilos.css" rel="stylesheet">
    <title>Vista de folio</title>
    <!-- Cualquier enlace a hojas de estilo o scripts necesarios -->
</head>

<?php if($data !== null){ ?>

<div class="container mt-4">
    <table class="table table-bordered">
        <tr>
            <td colspan="4" class="text-center">
                <h3>SOLICITUD CON FOLIO: <?php echo $folio ?></h3>
            </td>
        </tr>
        <tr>
            <td><b>Fecha solicitud:</b></td>
            <td colspan="3"><?php echo $data["fecha_solicitud"] ?></td>
        </tr>
        <tr>
            <td><b>Unidad Administrativa solicitante:</b></td>
            <td colspan="3"><?php echo $data["UR"] ?></td>
        </tr>
        <tr>
            <td><b>Edificio donde se suscitó el reporte:</b></td>
            <td><?php echo $data["SEDE"] ?></td>
        </tr>
        <tr>
            <td><b>Ubicación específica:</b></td>
            <td><?php echo $data["detalle_ubicacion_sol"] ?></td>
        </tr>
        <tr>
            <td><b>Tipo de servicio:</b></td>
            <td colspan="3"><?php echo $data["TIPO_SERVICIO"] ?></td>
        </tr>
        <tr>
            <td><b>Descripción del reporte:</b></td>
            <td colspan="3" style="height: 40px;"><?php echo $data["desc_reporte"] ?></td>
        </tr>

        <tr>
            <td colspan="4"><b>Fotografía de apoyo:</b></td>
        </tr>
        <tr>
            <td colspan="4" class="text-center">
                
            <?php
                    // Supongamos que $data["imagen_blob"] contiene la imagen en formato BLOB
                    $base64Image = base64_encode($data["file_image"]);
                    if($data["file_image"]!=null){
                        echo '<img src="data:'.$data["type_image"].';base64,' . $base64Image . '" class="img-fluid" alt="Imagen">';
                    }else{
                        echo "Sin imagen para mostrar";
                    }
                ?>

            </td>
        </tr>
        <tr>
            
        </tr>

        <?php if($tipo_consulta==1){?>

            <tr>
            <td colspan="4" class="text-center">
                <h3>DATOS DEL SOLICITANTE</h3>
            </td>
        </tr>
        <tr>
            <td><b>Nombre:</b></td>
            <td colspan="3"><?php echo $data["nombre_sol"] ?></td>
        </tr>
        <tr>
            <td><b>Correo:</b></td>
            <td colspan="3"><?php echo $data["correo_sol"] ?></td>
        </tr>
        <tr>
            <td><b>Extensión Cisco:</b></td>
            <td colspan="3"><?php echo $data["cisco"] ?></td>
        </tr>
        <tr>
            <td><b>Teléfono:</b></td>
            <td colspan="3"><?php echo $data["telefono_sol"] ?></td>
        </tr>
            <?php }else{?>

                <tr>
            <td><b>Estatus:</b></td>
            <td colspan="3"><?php echo $data["estatus"] ?></td>
        </tr>

                <?php }?>


    </table>
</div>


<?php
}else{
?>

<body>
<h3> El resultado no trajo información: </h3>
    <div class="alert alert-warning" role="alert" id="muestrafolio">
        
            <?php echo "Folio: <b>".$folio."</b>"; ?></br>
                    
    </div>
    <h3> Te recomentamos verificar nuevamente la información e intentar de nuevo.</h3>
</br>
    <button class="btn btn-primary" onclick="this.window.close();" >Cerrar esta ventana</button>

<?php
}

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="../bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script src="../js/muestra_folio.js"></script>