<?php
include "../utilities/Conect.php";
session_start();
// Verificar si el usuario tiene una sesión iniciada
if (!isset($_SESSION['username'])) {
    // Redirigir a la página principal si no hay sesión iniciada
    header("Location: ../index.php");
    exit(); // Asegurarse de que el script se detenga después de la redirección
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="../bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Font Awesome (if you are using it) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="../js/seguimiento_folios.js"></script>
    <title>Seguimiento a folios</title>
</head>

<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid align ">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="menus.php">
                    <img src="../images/logo.png" style="max-width:300px; margin-top:-16px; margin-left:-16px;"
                        class="img-thumbnail img-responsive"></a>
                </a>
            </div>
            <div class="collapse navbar-collapse display-user navbar-right" id="navbarText">
                <span class="navbar-text nombre-usuario text-primary border border-primary rounded p-2">
                    <i class="fas fa-user-circle"></i> <?php echo "<b>".$_SESSION['nombre_corto']."</b>"; ?>

                    <a href="../Control/cerrar_sesion.php">Cerrar Sesión</a>
                </span>


            </div>

        </div><!-- /.container-fluid -->
    </nav>
    <div class="floating-error alert alert-danger" role="alert" id="errorContainer" style="display: none;"></div>
    <div class="floating-success alert alert-success" role="alert" id="successContainer" style="display: none;"></div>
    <div class="floating-warning alert alert-warning" role="alert" id="warningContainer" style="display: none;"></div>

    <div class="container">

        <ul class="nav nav-tabs" role="tablist">

            <li role="presentation"><a href="#tabla1" aria-controls="tabla1" role="tab" data-toggle="tab">Sin turnar</a>
            </li>
            <li role="presentation"><a href="#tabla2" aria-controls="tabla2" role="tab" data-toggle="tab">Sin
                    atender</a>
            </li>
            <li role="presentation"><a href="#tabla3" aria-controls="tabla3" role="tab" data-toggle="tab">Atendidos</a>
            </li>
            <!-- Agrega más pestañas según tus necesidades -->
        </ul>

        <!-- Abre div principal -->
        <div class="tab-content">
            <!-- Abre tabpanel tabla 1 -->
            <div role="tabpanel" class="tab-pane active" id="tabla1">
                <?php
    $obj = new Conect();
    $con = $obj->getCon();
    $stmt = "SELECT id_reporte, folio, 
    fecha_solicitud, 
    a.ur_sol,
    (
   SELECT y.tipo_ur
   FROM cat_ur y
   WHERE y.id_ur=a.ur_sol) AS tipo_UR,
    (
   SELECT y.nombre_ur
   FROM cat_ur y
   WHERE y.id_ur=a.ur_sol) AS UR,
    (
   SELECT x.nombre_sede
   FROM cat_sedes x
   WHERE x.id_sede=a.id_sede) AS SEDE,
    nombre_sol,
    correo_sol,
    telefono_sol,
    cisco,
    (
   SELECT z.descripcion
   FROM catalogo z
   WHERE z.id_cat=a.id_tipo_servicio) AS SERVICIO,
    detalle_ubicacion_sol,
    desc_reporte,
    (
   SELECT CONCAT(z.nombre,' ',z.apellido1) AS nombre
   FROM cat_responsable z
   WHERE z.id_responsable=a.id_turnado) AS Responsable
   FROM reporte a
   WHERE estatus=4";
    $result = $con->query($stmt);

    if(mysqli_num_rows($result)>0){            
    ?>
                <!-- Abre table responsive-->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Días transcurridos</th>
                                <th>Tipo UR</th>
                                <th>UR</th>
                                <th>Edificio</th>
                                <th>Tipo</th>
                                <th>Descripción de solicitud</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
while ($row = $result->fetch_assoc()) {
$id_registro =$row["id_reporte"];
$folio = $row["folio"];
$tipo_ur= $row['tipo_UR'];
$sede = $row["SEDE"];
$fechaInicio = new DateTime($row["fecha_solicitud"]); // Fecha de inicio en formato 'YYYY-MM-DD'
$fechaFin = new DateTime();  // Fecha de fin en formato 'YYYY-MM-DD'
$intervalo = $fechaInicio->diff($fechaFin);
$diasTranscurridos = $intervalo->days;
$color ="";
if( $diasTranscurridos <=5){
    $color= "success";
}elseif($diasTranscurridos >5 && $diasTranscurridos <=10 ){
    $color= "warning";
}else{
    $color= "danger";
}
echo "<tr >";
echo "<td><a href='#' onclick=\"enviarPorPOST('$folio',1)\"> $folio </a></td>";
echo "<td class='alert alert-$color' role='alert'><a data-toggle='tooltip' data-placement='top' title='Fecha inicial: ".$fechaInicio->format('Y-m-d H:i:s')."'>".$diasTranscurridos."</a></td>";
echo "<td>$tipo_ur</td>";
echo "<td><a data-toggle='tooltip' data-placement='top' title='" . $row["UR"] . "'>" . $row["ur_sol"] . "</a></td>";
echo "<td>$sede</td>";
echo "<td>" . $row["SERVICIO"] . "</td>";
echo "<td>" . $row["desc_reporte"] . "</td>";
echo "</tr>";
}
$obj->closeCon($con);
?>
                        </tbody>
                    </table>
                </div>
                <!-- Cierra table responsive-->

                <?php }else{ ?>

                <div class="alert alert-success" role="alert">

                    <?php echo "Todos los turnados han sido atendidos."; ?></br>

                </div>
                <?php } ?>
            </div>
            <!-- Cierra table pane-->
            <!--Abre tabla 2-->
            <div role="tabpanel" class="tab-pane" id="tabla2">
                <?php
    $obj = new Conect();
    $con = $obj->getCon();
    $stmt = "SELECT id_reporte, folio, 
    fecha_solicitud, 
    a.ur_sol,
    (
   SELECT y.tipo_ur
   FROM cat_ur y
   WHERE y.id_ur=a.ur_sol) AS tipo_UR,
    (
   SELECT y.nombre_ur
   FROM cat_ur y
   WHERE y.id_ur=a.ur_sol) AS UR,
    (
   SELECT x.nombre_sede
   FROM cat_sedes x
   WHERE x.id_sede=a.id_sede) AS SEDE,
    nombre_sol,
    correo_sol,
    telefono_sol,
    cisco,
    (
   SELECT z.descripcion
   FROM catalogo z
   WHERE z.id_cat=a.id_tipo_servicio) AS SERVICIO,
    detalle_ubicacion_sol,
    desc_reporte,
    (
   SELECT CONCAT(z.nombre,' ',z.apellido1) AS nombre
   FROM cat_responsable z
   WHERE z.id_responsable=a.id_turnado) AS Responsable
   FROM reporte a
   WHERE estatus=5";
    $result = $con->query($stmt);

    if(mysqli_num_rows($result)>0){            
    ?>
                <!--Abre responsive tabla 2-->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Días transcurridos</th>
                                <th>TIPO UR</th>
                                <th>UR</th>
                                <th>Edificio</th>
                                <th>Tipo</th>
                                <th>Descripción de solicitud</th>
                                <!-- Agrega más encabezados según tus necesidades -->
                                <th>Responsable de atención</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
while ($row = $result->fetch_assoc()) {
$id_registro =$row["id_reporte"];
$folio = $row["folio"];
$responsable = $row["Responsable"];
$tipo_ur= $row['tipo_UR'];
$sede = $row["SEDE"];
$fechaInicio = new DateTime($row["fecha_solicitud"]); // Fecha de inicio en formato 'YYYY-MM-DD'
$fechaFin = new DateTime();  // Fecha de fin en formato 'YYYY-MM-DD'
$intervalo = $fechaInicio->diff($fechaFin);
$diasTranscurridos = $intervalo->days;
$color ="";
if( $diasTranscurridos <=5){
    $color= "success";
}elseif($diasTranscurridos >5 && $diasTranscurridos <=10 ){
    $color= "warning";
}else{
    $color= "danger";
}
echo "<tr >";
echo "<td><a href='#' onclick=\"enviarPorPOST('$folio',1)\"> $folio </a></td>";
echo "<td class='alert alert-$color' role='alert'><a data-toggle='tooltip' data-placement='top' title='Fecha inicial: ".$fechaInicio->format('Y-m-d H:i:s')."'>".$diasTranscurridos."</a></td>";
echo "<td>$tipo_ur</td>";
    echo "<td><a data-toggle='tooltip' data-placement='top' title='" . $row["UR"] . "'>" . $row["ur_sol"] . "</a></td>";
echo "<td>$sede</td>";
echo "<td>" . $row["SERVICIO"] . "</td>";
echo "<td>" . $row["desc_reporte"] . "</td>";
echo "<td>$responsable</td>";
echo "</tr>";
}
$obj->closeCon($con);
?>
                        </tbody>
                    </table>
                </div>
                <!--Cierra responsive tabla 2-->
                <?php }else{ ?>

                <div class="alert alert-success" role="alert">

                    <?php echo "Todo ha sido atendido"; ?></br>

                </div>
                <?php } ?>
            </div>
            <!--Cierra pane tabla 2-->

            <!--Abre pane tabla 3-->
            <div role="tabpanel" class="tab-pane" id="tabla3">
                <?php
    $obj = new Conect();
    $con = $obj->getCon();
    $stmt = "SELECT id_reporte, folio, 
    fecha_solicitud, 
    a.ur_sol,
        (
   SELECT y.tipo_ur
   FROM cat_ur y
   WHERE y.id_ur=a.ur_sol) AS tipo_UR,
    (
   SELECT y.nombre_ur
   FROM cat_ur y
   WHERE y.id_ur=a.ur_sol) AS UR,
    (
   SELECT x.nombre_sede
   FROM cat_sedes x
   WHERE x.id_sede=a.id_sede) AS SEDE,
    nombre_sol,
    correo_sol,
    telefono_sol,
    cisco,
    (
   SELECT z.descripcion
   FROM catalogo z
   WHERE z.id_cat=a.id_tipo_servicio) AS SERVICIO,
    detalle_ubicacion_sol,
    desc_reporte,
    (
   SELECT CONCAT(z.nombre,' ',z.apellido1) AS nombre
   FROM cat_responsable z
   WHERE z.id_responsable=a.id_turnado) AS Responsable,
   a.estatus,
(SELECT descripcion FROM catalogo z WHERE a.estatus=z.id_cat) AS estatus_desc
   FROM reporte a
   WHERE estatus IN (6,7)";
    $result = $con->query($stmt);

    if(mysqli_num_rows($result)>0){            
    ?>
                <!--Abre resposive tabla 3-->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Tipo UR</th>
                                <th>UR</th>
                                <th>Edificio</th>
                                <th>Tipo</th>
                                <th>Descripción de solicitud</th>
                                <!-- Agrega más encabezados según tus necesidades -->
                                <th>Responsable de atención</th>
                                <th>Estatus final</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
while ($row = $result->fetch_assoc()) {
$id_registro =$row["id_reporte"];
$folio = $row["folio"];
$responsable = $row["Responsable"];
$tipo_ur= $row['tipo_UR'];
$sede = $row["SEDE"];
$estatus_desc = $row["estatus_desc"];
$fechaInicio = new DateTime($row["fecha_solicitud"]); // Fecha de inicio en formato 'YYYY-MM-DD'
$fechaFin = new DateTime();  // Fecha de fin en formato 'YYYY-MM-DD'
$intervalo = $fechaInicio->diff($fechaFin);
$diasTranscurridos = $intervalo->days;
$color ="";
if( $diasTranscurridos <=5){
    $color= "success";
}elseif($diasTranscurridos >5 && $diasTranscurridos <=10 ){
    $color= "success";
}else{
    $color= "success";
}
echo "<tr >";
echo "<td><a href='#' onclick=\"enviarPorPOST('$folio',1)\"> $folio </a></td>";
echo "<td>$tipo_ur</td>";
echo "<td><a data-toggle='tooltip' data-placement='top' title='" . $row["UR"] . "'>" . $row["ur_sol"] . "</a></td>";
echo "<td>$sede</td>";
echo "<td>" . $row["SERVICIO"] . "</td>";
echo "<td>" . $row["desc_reporte"] . "</td>";
echo "<td>$responsable</td>";
echo "<td>$estatus_desc</td>";
// Agrega más columnas según tus necesidades
echo "</tr>";
}
$obj->closeCon($con);
?>
                        </tbody>
                    </table>
                </div>
                <!--Cierra resposive tabla 3-->
                <?php }else{ ?>

                <div class="alert alert-success" role="alert">

                    <?php echo "No hay folios atendidos"; ?></br>

                </div>
                <?php } ?>
            </div>

            <!-- Bootstrap JS (jQuery es necesario para el funcionamiento de Bootstrap JS) -->
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>