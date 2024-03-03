<?php
include_once "../utilities/Conect.php";
$obj = new Conect();
$con = $obj->getCon();

$folio = isset($_POST['folio'])? $_POST['folio'] : "";
$obj = new Conect();
        $con = $obj->getCon();
        $stmt = mysqli_prepare($con,"SELECT 
        a.folio,
        a.fecha_solicitud,
        b.fecha_inicio,
        b.fecha_fin,
        if(a.estatus=7,c.observaciones,if(a.estatus=6,b.desc_actividad,'Sin descripción')) AS descripcion,
        a.estatus,
        (SELECT descripcion FROM catalogo z WHERE a.estatus=z.id_cat) AS estatus_desc
        FROM reporte a
        LEFT join atencion b on a.id_reporte=b.id_registro
        LEFT JOIN baja_reporte c on a.id_reporte=c.id_registro
        WHERE a.folio=?");
        mysqli_stmt_bind_param($stmt,'s', $folio);
        /* ejecuta sentencias preparadas */
        mysqli_stmt_execute($stmt);
        $rs = mysqli_stmt_get_result($stmt);
        $obj->closeCon($con);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="../bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
     <!-- Font Awesome (if you are using it) -->
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <title>Busca tu folio</title>
</head>
<body>
    <br/>
<div class="container mt-40px">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered">

    <?php
            if (mysqli_num_rows($rs)>0){
                if ($row = $rs->fetch_assoc()) {
                ?>
                <thead>
                        <tr>
                            <th>FOLIO</th>
                            <th>FECHA DE SOLICITUD</th>
                            <th>ESTATUS</th>
                            <th>DESCRIPCIÓN</th>
                        </tr>
                    </thead>
                        <tbody>
                        <tr>
                            <td><?php echo $row['folio']; ?></td>
                            <td><?php echo $row['fecha_solicitud']; ?></td>
                            <td><?php echo $row['estatus_desc']; ?></td>
                            <td><?php echo $row['descripcion']; ?></td>
                        </tr>
                        </tbody>
                
    <?php         
           } }else{
                echo "<p>El folio no esta en la base de datos, verificar si está bien escrito.</p>";
            }
    ?>
                 </table>
                </div>
        </div>
    </div>
</div>
</body>
</html>