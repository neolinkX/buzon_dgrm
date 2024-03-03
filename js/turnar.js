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

  function cargarSelect(select,idSelect) {
    // Aquí puedes hacer una petición AJAX para obtener las sedes desde el servidor
    // y luego llenar las opciones del menú desplegable
    // Por ejemplo:
    $.ajax({
        url: '../utilities/obten_select.php', // Cambia esto por la ruta real
        method: 'GET',
        data: {
            parametro: select
            // Agrega más parámetros si es necesario
        },
        dataType: 'json',
        success: function(data) {
            
            var varSelect = $(idSelect);
            varSelect.empty(); // Limpiar opciones actuales
            varSelect.append('<option value=0 selected> Selecciona.. </option>');
            
            // Agregar las nuevas opciones basadas en los datos recibidos
            for (var i = 0; i < data.length; i++) {
                
                
                varSelect.append($('<option>', {
                    value: data[i].id,
                    text: data[i].nombre
                    
                }));
            }
            
        },
        error: function() {
            // Manejar error si la petición falla
            console.log("Error en carga Select");
        }
    });
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


  function seleccion(){
    id_select = $("#id_turnar").val();
    id_reporte= $("#idRegistroOculto").val();
    if(id_select==0){
        alert('Por favor selecciona un residente.');
        return
    }
    if(confirm("¿Estás seguro/a de asignar este reporte?")){
    var xhr = new XMLHttpRequest();
    var params = "id_select="+id_select+"&id_reporte="+id_reporte+"&estatus=5"; 
    xhr.open("POST", "../Control/cambiar_estatus_reporte.php", true);
     // Establecer el encabezado para enviar datos por método POST
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
            
            if (xhr.status === 200) {
                
                var respuesta = xhr.responseText;
               
                if (respuesta === "OK") {
                    mostrarSuccess("Se cambió correctamente");
                    // Usuario autenticado, redirigir a una página
                   window.location.href = "turnar.php";
                    //document.getElementById("miIframe").src = "Vista/dashboard.php"; // Reemplaza "nueva_pagina.html" con la página que deseas cargar
                } else{
                    // Mostrar mensaje de error
                    mostrarError("Se tuvo un error al turnar. Vuelva a intentarlo más tarde.");
                }
            } else {
                // Manejo de errores de red u otros
                mostrarError("Error en la solicitud. Inténtalo de nuevo más tarde.")
            }
        }
    };
    xhr.send(params);
}
}

function abrirFormularioModal(id_registro, folio, sede, id_sede) {
    $('#formularioModal #folio').text(folio);
    $('#formularioModal #sede').text(sede);
    $('#formularioModal #idRegistroOculto').val(id_registro); // Establecer el valor en el input hidden

    var select = "SELECT a.id_responsable as id, CONCAT(a.nombre,' ',a.apellido1) AS nombre FROM cat_responsable a, relacion_responsable_sede b WHERE a.id_responsable=b.id_responsable AND a.activo=1 AND b.activo =1 AND b.id_sede="+id_sede+" order by orden asc";

    var idSelect = '#id_turnar';
    cargarSelect(select,idSelect);
    $('#formularioModal').modal('show');

}


