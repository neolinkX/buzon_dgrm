<?php
include "../utilities/Conect.php";
session_start();
// Verificar si el usuario tiene una sesión iniciada
if (!isset($_SESSION['username'])) {
    // Redirigir a la página principal si no hay sesión iniciada
    header("Location: ../index.php");
    exit(); // Asegurarse de que el script se detenga después de la redirección
}else{
    $id_responsable = $_SESSION["id_responsable"] ;
}

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
    <script src="../js/dashboard.js"></script>
    <title>Buzón DGRM</title>
</head>

<body>
    <nav class="navbar navbar-default">
    <div class="container-fluid align ">
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
                <a class="navbar-brand" href="#">
						<img src="../images/logo.png" style="max-width:300px; margin-top:-16px; margin-left:-16px;" class="img-thumbnail img-responsive"></a>
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


<!-- Abre container-->
    <div class="container">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">

            <li role="presentation"><a href="#tabla1" aria-controls="tabla1" role="tab" data-toggle="tab">Turnados</a>
            </li>
            <li role="presentation"><a href="#tabla2" aria-controls="tabla2" role="tab" data-toggle="tab">Descargar</a>
            </li>
            <li role="presentation"><a href="#tabla3" aria-controls="tabla3" role="tab" data-toggle="tab">Baja</a>
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
               WHERE estatus=5 AND id_turnado=$id_responsable";
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
                                <th>UR</th>
                                <th>Edificio</th>
                                <th>Tipo</th>
                                <th>Descripción de solicitud</th>
                                <!-- Agrega más encabezados según tus necesidades -->
                                <th>Responsable de atención</th>
                                <th>Dar por atendido</th>
                                <th>Dar de baja</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
        while ($row = $result->fetch_assoc()) {
            $id_registro =$row["id_reporte"];
            $folio = $row["folio"];
            $responsable = $row["Responsable"];
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
            echo "<td><a data-toggle='tooltip' data-placement='top' title='" . $row["UR"] . "'>" . $row["ur_sol"] . "</a></td>";
            echo "<td>$sede</td>";
            echo "<td>" . $row["SERVICIO"] . "</td>";
            echo "<td>" . $row["desc_reporte"] . "</td>";
            echo "<td>$responsable</td>";
            // Agrega más columnas según tus necesidades
            echo "<td style='text-align: center;' >";
            echo "<button type='button' class='btn btn-primary' onclick=\"abrirFormularioModal('$id_registro', '$folio', '$responsable', '$sede')\">Atender</button>";                
            echo "</td>";

            echo "<td style='text-align: center;' >";
            echo "<button type='button' class='btn btn-primary' onclick=\"abrirFormularioModalBajas('$id_registro', '$folio', '$sede')\">Baja</button>";                
            echo "</td>";

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
               WHERE estatus=6 AND id_turnado=$id_responsable";
                $result = $con->query($stmt);
        
                if(mysqli_num_rows($result)>0){            
                ?>
                <!--Abre responsive tabla 2-->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>UR</th>
                                <th>Edificio</th>
                                <th>Tipo</th>
                                <th>Descripción de solicitud</th>
                                <!-- Agrega más encabezados según tus necesidades -->
                                <th>Responsable de atención</th>
                                <th>Imprimir documento de atención</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
        while ($row = $result->fetch_assoc()) {
            $id_registro =$row["id_reporte"];
            $folio = $row["folio"];
            $responsable = $row["Responsable"];
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
                echo "<td><a data-toggle='tooltip' data-placement='top' title='" . $row["UR"] . "'>" . $row["ur_sol"] . "</a></td>";
            echo "<td>$sede</td>";
            echo "<td>" . $row["SERVICIO"] . "</td>";
            echo "<td>" . $row["desc_reporte"] . "</td>";
            echo "<td>$responsable</td>";
            // Agrega más columnas según tus necesidades
            echo "<td style='text-align: center;' >";
            
            echo "<a href=\"../Control/genera_hoja_servicio.php?id_registro=$id_registro\" target='_blank' >
            <img src='../images/pdf_download.png' alt='Icono de PDF' class='mr-2'>
            </a>";                
            echo "</td>";
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

                    <?php echo "No hay nada por acá."; ?></br>

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
               SELECT observaciones
               FROM baja_reporte z
               WHERE z.id_registro=a.id_reporte) AS motivo_baja,
                (
               SELECT CONCAT(z.nombre,' ',z.apellido1) AS nombre
               FROM cat_responsable z
               WHERE z.id_responsable=a.id_turnado) AS Responsable
               FROM reporte a
               WHERE estatus=7 AND id_turnado=$id_responsable";
                $result = $con->query($stmt);
        
                if(mysqli_num_rows($result)>0){            
                ?>
                <!--Abre resposive tabla 3-->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Días transcurridos</th>
                                <th>UR</th>
                                <th>Edificio</th>
                                <th>Tipo</th>
                                <th>Descripción de solicitud</th>
                                <!-- Agrega más encabezados según tus necesidades -->
                                <th>Responsable de atención</th>
                                <th>Motivo Baja</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
        while ($row = $result->fetch_assoc()) {
            $id_registro =$row["id_reporte"];
            $folio = $row["folio"];
            $responsable = $row["Responsable"];
            $sede = $row["SEDE"];
            $motivo_baja = $row["motivo_baja"];
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
            echo "<td class='alert alert-$color' role='alert'><a data-toggle='tooltip' data-placement='top' title='Fecha inicial: ".$fechaInicio->format('Y-m-d H:i:s')."'>".$diasTranscurridos."</a></td>";
            echo "<td><a data-toggle='tooltip' data-placement='top' title='" . $row["UR"] . "'>" . $row["ur_sol"] . "</a></td>";
            echo "<td>$sede</td>";
            echo "<td>" . $row["SERVICIO"] . "</td>";
            echo "<td>" . $row["desc_reporte"] . "</td>";
            echo "<td>$responsable</td>";
            echo "<td>$motivo_baja</td>";
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

                    <?php echo "No tienes folios dados de baja."; ?></br>

                </div>
                <?php } ?>
            </div>
            <!--Cierra pane tabla 3-->
            
            <!-- Agrega más tablas en pestañas según tus necesidades -->
        </div>
        <!--Cierra div general-->
    </div>

    <!-- Inicia modal -->
    <!-- Modal -->
    <div class="modal fade" id="formularioModal" tabindex="-1" role="dialog" aria-labelledby="formularioModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 class="modal-title" id="formularioModalLabel" style="text-align:center;">Atención al Folio:
                        <b><span id="folio"></span></b></h4>
                    <br />
                    <form action="../Control/registro_atencion.php" method="POST" name="form1">
                        <div class="form-group">
                            <label for="sede">Lugar del reporte:</label>
                            <b><span id="sede"></span></b>
                        </div>
                        <div class="form-group">
                            <label for="responsable">Responsable de la sede:</label>
                            <b><span id="responsable"></span></b>
                        </div>
                        <div class="form-group" id="MantenimientoDiv"  >
                            <label for="id_mantenimiento">Tipo de Mantenimiento:</label>
                            <select class="form-control selectpicker"  id="id_mantenimiento" name="id_mantenimiento">
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="proveedor">¿Atendido a través de un proveedor?</label>
                            <select class="form-control" id="proveedor" name="proveedor">
                            <option value=0> Selecciona.. </option>
                                <option value="si">Sí</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="form-group" id="contratoDiv" style="display: none;" >
                            <label for="idContrato">Contrato utilizado:</label>
                            <select class="form-control selectpicker"  id="idContrato" name="idContrato">
                            </select>
                        </div>
                        <div class="form-group" id="materialDiv" style="display: none;">
                            <label for="material">Describa el material consumido:</label>
                            <textarea class="form-control" id="material" name="material" rows="3" maxlength="150"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="fechaInicio">Fecha de inicio:</label>
                            <input type="date" class="form-control" id="fechaInicio" name="fechaInicio">
                        </div>
                        <div class="form-group">
                            <label for="fechaTermino">Fecha de término:</label>
                            <input type="date" class="form-control" id="fechaTermino" name="fechaTermino">
                        </div>
                        <div class="form-group">
                            <label for="actividad">Describa la actividad realizada:</label>
                            <textarea class="form-control" id="actividad" name="actividad" rows="3" minlength="1" maxlength="250"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="observaciones">Observaciones:</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                        </div>
                                    
                            <input type="hidden" id="idRegistroOculto" name="idRegistroOculto">
                        
                       
                    </form>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="enviar();" >Guardar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="formularioModalBaja" tabindex="-1" role="dialog" aria-labelledby="formularioModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 class="modal-title" id="formularioModalLabel" style="text-align:center;">Baja del Folio:
                        <b><span id="folioB"></span></b></h4>
                    <br />
                    <form action="../Control/registro_baja.php" method="POST" name="form2">
                    <div class="form-group">
                            <label for="sedeB">Lugar del reporte:</label>
                            <b><span id="sedeB"></span></b>
                        </div>
                        <div class="form-group">
                            <label for="observacionesB">Describa el motivo de la baja:</label>
                            <textarea class="form-control" id="observacionesB" name="observacionesB" rows="3"></textarea>
                        </div>
                                    
                            <input type="hidden" id="idRegistroOcultoB" name="idRegistroOcultoB">
                    </form>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="baja();" >Dar de baja</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    
                </div>
            </div>
        </div>
    </div>



</body>

</html>