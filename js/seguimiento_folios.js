$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
  });

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

function enviarPorPOST(folio,id_tipo_consulta_folio) {
    // Crear un objeto XMLHttpRequest
    var xhr = new XMLHttpRequest();

    // Especificar el tipo de solicitud y la URL de destino
    xhr.open('POST', '../Vista/muestra_reporte_detalle.php', true);

    // Establecer el tipo de contenido de la solicitud POST
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Definir la función de devolución de llamada para manejar la respuesta
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        // Manejar la respuesta del servidor si es necesario
        // Puedes agregar código aquí para manejar la respuesta del servidor
        window.open('', '_blank').document.write(xhr.responseText);

      }
    };

    // Construir los datos a enviar
    var datos = 'folio=' + encodeURIComponent(folio) + '&id_tipo_consulta_folio=' + encodeURIComponent(id_tipo_consulta_folio);

    // Enviar la solicitud con los datos
    xhr.send(datos);

    // Evitar que el enlace redireccione
    return false;
  }