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
    <script src="../bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <!-- Font Awesome (if you are using it) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="../js/turnar.js"></script>
    <title>Turnar Reportes</title>
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

    <div class="container">
    
    <?php
                $obj = new Conect();
                $con = $obj->getCon();
                $stmt = "SELECT id_reporte, folio, 
                fecha_solicitud, 
                a.ur_sol,
                (SELECT y.nombre_ur FROM cat_ur y where y.id_ur=a.ur_sol) AS UR,
                a.id_sede,
                (SELECT x.nombre_sede FROM cat_sedes x where x.id_sede=a.id_sede) AS SEDE,
                nombre_sol,
                correo_sol,
                telefono_sol,
                cisco,
                (SELECT z.descripcion FROM catalogo z where z.id_cat=a.id_tipo_servicio) AS SERVICIO,
                detalle_ubicacion_sol,
                desc_reporte
                FROM reporte a where estatus=4";
                $result = $con->query($stmt);
        
                if(mysqli_num_rows($result)>0){            
                ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Días transcurridos</th>
                                <th>UR</th>
                                <th>Edificio</th>
                                <th>Tipo de Servicio</th>
                                <th>Descripción de solicitud</th>
                                <!-- Agrega más encabezados según tus necesidades -->
                                <th>Turnar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
        while ($row = $result->fetch_assoc()) {
            
            $fechaInicio = new DateTime($row["fecha_solicitud"]); // Fecha de inicio en formato 'YYYY-MM-DD'
            $fechaFin = new DateTime();  // Fecha de fin en formato 'YYYY-MM-DD'
            $folio = $row['folio'];
            $sede = $row["SEDE"];
            $id_sede= $row['id_sede'];
            $id_registro = $row["id_reporte"];
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

            echo "<tr>";
            echo "<td><a href='#' onclick=\"enviarPorPOST('$folio',1)\"> $folio </a></td>";
            echo "<td class='alert alert-$color' role='alert'><a data-toggle='tooltip' data-placement='top' title='Fecha inicial: ".$fechaInicio->format('Y-m-d H:i:s')."'>".$diasTranscurridos."</a></td>";
            echo "<td><a data-toggle='tooltip' data-placement='top' title='" . $row["UR"] . "'>" . $row["ur_sol"] . "</a></td>";
            echo "<td> $sede </td>";
            echo "<td>" . $row["SERVICIO"] . "</td>";
            echo "<td>" . $row["desc_reporte"] . "</td>";
            // Agrega más columnas según tus necesidades
            echo "<td>";
            echo "<button type='button' class='btn btn-primary' onclick=\"abrirFormularioModal('$id_registro', '$folio', '$sede','$id_sede')\">Turnar</button>";                
        
            echo "</td>";
            echo "</tr>";
        }
        $obj->closeCon($con);
        ?>
                        </tbody>
                    </table>
                </div>
                <?php }else{ ?>

                <div class="alert alert-success" role="alert">

                    <?php echo "Nada por turnar."; ?></br>

                </div>
                <?php } ?>
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
                    <h4 class="modal-title" id="formularioModalLabel" style="text-align:center;">Turnar Folio:
                        <b><span id="folio"></span></b></h4>
                    <br />
                    <form name="form1">
                        <div class="form-group">
                            <label for="sede">Lugar del reporte:</label>
                            <b><span id="sede"></span></b>
                        </div>
                        
                        <div class="form-group" id="ResponsableDiv">
                            <label for="id_turnar">Responsable de atención:</label>
                            <select class="form-control selectpicker"  id="id_turnar" name="id_turnar">
                            </select>
                        </div>                                    
                            <input type="hidden" id="idRegistroOculto" name="idRegistroOculto">                    
                    </form>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="seleccion();" >Turnar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    
                </div>
            </div>
        </div>
    </div>