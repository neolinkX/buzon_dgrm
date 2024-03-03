

function ingresa() {
    
    var xhr = new XMLHttpRequest();
    var username= document.getElementById("username").value;
    var password= document.getElementById("password").value;
    var params = "username="+username+"&password="+password; // Datos a enviar en formato clave=valor
    xhr.open("POST", "Control/control_usuario.php", true);
     // Establecer el encabezado para enviar datos por método POST
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
            
            if (xhr.status === 200) {
                
                var respuesta = xhr.responseText;
               
                if (respuesta === "autenticado") {
                    mostrarSuccess("Autenticación correcta. Bienvenido.");
                    // Usuario autenticado, redirigir a una página
                   window.location.href = "Vista/menus.php";
                    //document.getElementById("miIframe").src = "Vista/dashboard.php"; // Reemplaza "nueva_pagina.html" con la página que deseas cargar
                } else if (respuesta === "error") {
                    // Mostrar mensaje de error
                    mostrarError("Usuario y/o Contraseña Incorrecto.");
                }
            } else {
                // Manejo de errores de red u otros
                mostrarError("Error en la solicitud. Inténtalo de nuevo más tarde.")
            }
        }
    };
    xhr.send(params);
}



function mostrarError(mensaje) {
    var errorContainer = document.getElementById("errorContainer");
    errorContainer.innerHTML = mensaje;
    $(errorContainer).fadeIn();
    setTimeout(function() {
        $(errorContainer).fadeOut();
    }, 3000); // Desaparecer después de 3 segundos
}

function mostrarSuccess(mensaje) {
    var successContainer = document.getElementById("successContainer");
    successContainer.innerHTML = mensaje;
    $(successContainer).fadeIn();
    setTimeout(function() {
        $(successContainer).fadeOut();
    }, 3000); // Desaparecer después de 3 segundos
}

function mostrarWarning(mensaje) {
    var warningContainer = document.getElementById("warningContainer");
    warningContainer.innerHTML = mensaje;
    $(warningContainer).fadeIn();
    setTimeout(function() {
        $(warningContainer).fadeOut();
    }, 3000); // Desaparecer después de 3 segundos
}
