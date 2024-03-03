<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/estilos.css">
    <script src="js/principal.js"></script>
    <title>Buzón DGRM</title>
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
						<img src="images/logo.png" style="max-width:300px; margin-top:-16px; margin-left:-16px;" class="img-thumbnail img-responsive"></a>
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                <form class="navbar-form navbar-right">
                    <div class="form-group">
                        Si eres usuario administrador, ingresa aquí:
                        <input type="text" class="form-control" id="username" placeholder="Usuario">
                        <input type="password" class="form-control" id="password" placeholder="Contraseña">
                    </div>
                    <button type="button" class="btn btn-default" onclick="ingresa()">Ingresa</button>
                </form>

            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    <div class="floating-error alert alert-danger" role="alert" id="errorContainer" style="display: none;"></div>
    <div class="floating-success alert alert-success" role="alert" id="successContainer" style="display: none;"></div>
    <div class="floating-warning alert alert-warning" role="alert" id="warningContainer" style="display: none;"></div>

    <div id="iframeContainer" class="container" style="height: 1300px;">
        <iframe id="miIframe" src="Vista/default.php" scrolling="no"></iframe>
    </div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
</body>

</html>