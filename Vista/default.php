<?php
session_start();
$error_message="";

// Verificar si hay un mensaje de error en la sesión
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Limpiar la variable de sesión
}
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/estilos.css" rel="stylesheet">
    <title>Formulario</title>
    <!-- Cualquier enlace a hojas de estilo o scripts necesarios -->
</head>

<body>
    <!-- Contenido de la página -->
    <header>
        <h3>¿Quieres solicitar un servicio a las instalaciones de tu edificio de trabajo, exteriores o áreas
            comunes?</h3>

        <h2>Realiza tu reporte aquí</h2>
        <br />
    </header>

    <main>
        <form name="form1" id="form1" action="../Control/registro_reportes.php" method="POST" enctype="multipart/form-data">
            <div class="row">
            <div class="col-md-6 question">
                    <label for="ur_sol">Tipo UR</label>
                    <select class="form-control selectpicker" data-live-search="true" id="tipo_ur" name="tipo_ur" onChange="cargarUr(this.value)">
                        <option value="CENTRALES">CENTRALES</option>
                        <option value="ESTADOS">ESTADOS</option>
                    </select>
                </div>
                <div class="col-md-6 question">
                    <label for="ur_sol">UR Solicitante:</label>
                    <select class="form-control selectpicker" data-live-search="true" id="ur_sol" name="ur_sol" onChange="cargarSede(this.value)">
                    </select>
                </div>
                <div class="col-md-6 question">
                    <label for="nombre_sol">Nombre del Solicitante:</label>
                    <input type="text" id="nombre_sol" name="nombre_sol" maxlength="255" class="form-control"
                        placeholder="Captura tu nombre completo, empezando por apellidos" required>
                </div>
                <div class="col-md-6 question">
                    <label for="correo_sol">Correo del solicitante:</label>
                    <input type="email" id="correo_sol" name="correo_sol" maxlength="255" class="form-control"
                        placeholder="Captura un correo electrónico válido" required>
                </div>
                <div class="col-md-6 question">
                    <label for="cisco">Extensión Cisco:</label>
                    <input type="tel" id="cisco" name="cisco" class="form-control"
                        placeholder="Captura una extensión telefónica" required>
                </div>
                <div class="col-md-6 question">
                    <label for="telefono">Teléfono directo o particular (opcional):</label>
                    <input type="tel" id="telefono" name="telefono" class="form-control">
                </div>

                <div class="col-md-6 question">
                    <label for="id_sede">Sede en donde solicitas el servicio:</label>
                    <select class="form-control selectpicker" data-live-search="true" id="id_sede" name="id_sede">
                    </select>
                </div>
                <div class="col-md-6 question">
                    <label for="id_tipo_servicio">Tipo de Servicio:</label>
                    <select class="form-control selectpicker" data-live-search="true" id="id_tipo_servicio"
                        name="id_tipo_servicio">
                    </select>
                </div>
                <div class="col-md-12 question">
                    <label for="detalle_ubicacion_sol">Detalla el lugar donde se encuentra esta situación:</label>
                    <textarea id="detalle_ubicacion_sol" name="detalle_ubicacion_sol"
                        placeholder="Ejemplo: Ala oeste, piso 5, baño de hombres, etc." class="form-control" rows="1"
                        minlength="1" maxlength="100" required></textarea>
                </div>
                <div class="col-md-12 question">
                    <label for="desc_reporte">Descripción del Reporte:</label>
                    <textarea id="desc_reporte" minlength="1" maxlength="250" name="desc_reporte" class="form-control"
                        rows="3" placeholder="Describe brevemente la situación de tu solicitud" required></textarea>
                </div>
                
                <div class="col-md-12 question">
                    <label for="imagen">Carga una foto de tu solicitud (Opcional):</label>
                    <input type="file" name="imagen" id="imagen" accept="image/*">
                </div>
                <div class="col-md-12 text-center question">
                    <button class="btn btn-primary" type="submit" >Enviar</button>
                </div>
            </div>
        </form>
      
        <?php if (strlen($error_message)>0){ ?>
        <div class="alert alert-warning" role="alert">
            <?php echo $error_message; ?>
        </div>
        <?php } ?>

    </main>

    <footer>
        <!-- Contenido del pie de página -->
    </footer>

    <!-- Cualquier script necesario -->
</body>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="../bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script src="../js/default.js"> </script>
<!-- Include all compiled plugins (below), or include individual files as needed -->


</html>