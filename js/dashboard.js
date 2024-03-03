$(document).ready(function() {

        $('[data-toggle="tooltip"]').tooltip();

        var select = "SELECT id_responsable as id, CONCAT(z.nombre,' ',z.apellido1) AS nombre FROM cat_responsable z WHERE activo=1";
        var idSelect = '[data-live-search="true"]';
        if ($(idSelect).length > 0){
        cargarSelect(select,idSelect);
        }

        const prov = document.getElementById("proveedor");
  
        prov.addEventListener("change", (event) => {
            
            if (event.target.value === "si") {
               
                $("#contratoDiv").show();
                $("#materialDiv").hide();
            } if (event.target.value === "no") {
                $("#contratoDiv").hide();
                $("#materialDiv").show();  
            } 
        });


        
    });



    function abrirFormularioModal(id_registro, folio, responsable, sede) {
        $('#formularioModal #folio').text(folio);
        $('#formularioModal #responsable').text(responsable);
        $('#formularioModal #sede').text(sede);
        $('#formularioModal #idRegistroOculto').val(id_registro); // Establecer el valor en el input hidden
        var select = "SELECT z.id_cat AS id, z.descripcion AS nombre FROM catalogo z WHERE z.activo=1 AND z.id_grupo=3";
        var idSelect = "#id_mantenimiento";
        cargarSelect(select,idSelect);

        var select = "SELECT z.id_cat AS id, z.descripcion AS nombre FROM catalogo z WHERE z.activo=1 AND z.id_grupo=4";
        var idSelect = '#idContrato';
        cargarSelect(select,idSelect);
        $('#formularioModal').modal('show');
    }

    function abrirFormularioModalBajas(id_registro, folio, sede) {
        $('#formularioModalBaja #folioB').text(folio);
        $('#formularioModalBaja #sedeB').text(sede);
        $('#formularioModalBaja #idRegistroOcultoB').val(id_registro); // Establecer el valor en el input hidden      
        $('#formularioModalBaja').modal('show');
    }
  
    function enviar(){

        document.form1.submit();
    }

    function baja(){

        document.form2.submit();
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