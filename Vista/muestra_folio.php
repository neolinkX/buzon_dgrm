<?php

$folio = isset($_GET['folio'])? $_GET['folio'] : "";
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/estilos.css" rel="stylesheet">
    <title>Formulario</title>
    <!-- Cualquier enlace a hojas de estilo o scripts necesarios -->
</head>
<body>
<h3 id="Aqui"> Tu folio para dar seguimiento a este reporte es: </h3>
    <div class="alert alert-warning" role="alert" id="muestrafolio">
        
            <?php echo "Folio: <b>".$folio."</b>"; ?></br>
                    
    </div>
    <h3> Te recomendamos tenerlo a la mano y dar seguimiento a través de este sistema.</h3>
</br>
    <button class="btn btn-primary" id="btredireccion" >¿Quieres levantar un nuevo reporte?</button>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="../bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script src="../js/muestra_folio.js"></script>
<script>
// Enfocar el elemento al cargar la página
function desplazar() {
    
    var html = parent.document.documentElement;
    
    
    html.scrollIntoView({ behavior: 'smooth' });
}

setTimeout(desplazar, 1);
</script>


</body>