<?php
include "../utilities/Conect.php";
session_start();
// Verificar si el usuario tiene una sesión iniciada
if (!isset($_SESSION['username'])) {
    // Redirigir a la página principal si no hay sesión iniciada
    header("Location: ../index.php");
    exit(); // Asegurarse de que el script se detenga después de la redirección
}else{
    $id = $_SESSION["id"];
    $obj = new Conect();
    $con = $obj->getCon();
    $stmt = "SELECT *
    FROM users a, permisos_menu b, menu c
    WHERE a.id_tipo_usuario=b.id_tipo_usuario AND 
    b.id_menu=c.id_menu 
    AND a.id=$id";
    
    $result = $con->query($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="../bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (if you are using it) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">

    <!-- Menu JavaScript -->
    <script src="../js/menu.js"></script>

    <title>Menú inicial</title>
</head>

<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
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
                    <img src="../images/logo.png" style="max-width:300px; margin-top:-16px; margin-left:-16px;"
                        class="img-thumbnail img-responsive">
                </a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php
                        while ($row = $result->fetch_assoc()) {
                            $desc_menu = $row['desc_menu'];
                            $pagina = $row['pagina'];
                            echo "<li><a href='$pagina'>$desc_menu</a></li>";
                        }
                        $obj->closeCon($con);
                    ?>
                </ul>
                <p class="navbar-text navbar-right">
                    <i class="fas fa-user-circle"></i>
                    <?php echo "<b>".$_SESSION['nombre_corto']."</b>"; ?>
                    <a href="../Control/cerrar_sesion.php">Cerrar Sesión</a>
                </p>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>

    <div class="container">
        Bienvenido(a), esta es la página principal. Por favor selecciona una opción del menú superior.
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="../bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
</body>

</html>