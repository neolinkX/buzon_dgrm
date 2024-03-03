$(document).ready(function() {
    // Cargar las sedes en el menú desplegable al cargar la página
    var select = "Select id_sede as id, nombre_sede as nombre from cat_sedes where activo=1 and nombre_sede not like '%S.C.T%'";
    var idSelect = "#id_sede";
    cargarSelect(select,idSelect);

    var selectTipoUr = $("#tipo_ur").val();


    var select = "SELECT b.id_cat as id, b.descripcion as  nombre from grupo_catalogo a, catalogo b WHERE a.id_grupo=b.id_grupo and a.activo = b.activo AND b.activo=1 AND b.id_grupo=2 ORDER BY b.id_sub";
    var idSelect = "#id_tipo_servicio";
    cargarSelect(select,idSelect);
    

    var select = "Select id_ur as id, concat(id_ur,' - ', nombre_ur) as nombre from cat_ur where tipo_ur = '"+selectTipoUr+"' and activo=1 order by id_ur";
    var idSelect = "#ur_sol";
    cargarSelect(select,idSelect);

    $("#telefono").inputmask({
        mask: "(999) 999-9999" // Define la máscara de teléfono
    });

    $("#cisco").inputmask({
        mask: "99999" // Define la máscara de teléfono
    });



    
});

function cargarUr(tipoUr) {
    var select = "Select id_ur as id, concat(id_ur,' - ', nombre_ur) as nombre from cat_ur where tipo_ur = '"+tipoUr+"' and activo=1 order by id_ur";
    var idSelect = "#ur_sol";
    cargarSelect(select, idSelect);

    var select = "SELECT b.id_cat as id, b.descripcion as  nombre from grupo_catalogo a, catalogo b WHERE a.id_grupo=b.id_grupo and a.activo = b.activo AND b.activo=1 AND b.id_grupo=2 ORDER BY b.id_sub";
    var idSelect = "#id_tipo_servicio";
    if(tipoUr=="ESTADOS"){
        select = "SELECT b.id_cat as id, b.descripcion as  nombre from grupo_catalogo a, catalogo b WHERE a.id_grupo=b.id_grupo and a.activo = b.activo AND b.activo=1 AND b.id_grupo=2 and id_cat not in (15) ORDER BY b.id_sub";
    }
    cargarSelect(select,idSelect);
    var select = "Select id_sede as id, nombre_sede as nombre from cat_sedes where activo=1 and nombre_sede not like '%S.C.T%'";
    var idSelect = "#id_sede";

    if(tipoUr=="ESTADOS"){
        select = "Select id_sede as id, nombre_sede as nombre from cat_sedes where activo=1 and nombre_sede = 'Centro S.C.T.  Aguascalientes'";
    }
    cargarSelect(select,idSelect);

}

function cargarSede(ur){
    

    if(ur>=621 && ur<=652 ){
        var idSelect = "#id_sede";
         var select = "SELECT a.id_sede as id, a.nombre_sede as nombre from cat_sedes a, cat_ur b WHERE a.nombre_sede=b.nombre_ur and a.activo=1 AND b.id_ur="+ur;
        cargarSelect(select,idSelect);
    }
    
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
            var sedeSelect = $(idSelect);
            sedeSelect.empty(); // Limpiar opciones actuales
            // Agregar las nuevas opciones basadas en los datos recibidos
            for (var i = 0; i < data.length; i++) {
                sedeSelect.append($('<option>', {
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