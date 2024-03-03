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

                
            <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                                aria-expanded="false">Busca tu folio<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li>
                            <form id="miFormulario" class="navbar-form" action="Vista/muestra_folio_poblacion.php" target="popup" method="post" onsubmit="window.open('', 'popup', 'width=800,height=200')">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="folio" id="folio" placeholder="Escribe tu folio">
                                        
                                    </div>
                                    <button type="submit" class="btn btn-defaul" >Buscar</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                            aria-expanded="false">Si eres usuario administrador, ingresa aquí <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li>
                                <form class="navbar-form">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="username" placeholder="Usuario">
                                        <input type="password" class="form-control" id="password" placeholder="Contraseña">
                                    </div>
                                    <button type="button" class="btn btn-default" onclick="ingresa()">Ingresa</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    
                </ul>

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