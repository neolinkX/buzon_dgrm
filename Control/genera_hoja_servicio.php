<?php
require_once('../TCPDF/tcpdf.php'); // Ajusta la ruta según la ubicación de la biblioteca TCPDF
include "../utilities/Conect.php";
session_start();
// Verificar si el usuario tiene una sesión iniciada
if (!isset($_SESSION['username'])) {
    // Redirigir a la página principal si no hay sesión iniciada
    header("Location: ../index.php");
    exit(); // Asegurarse de que el script se detenga después de la redirección
}

        $id_registro = isset($_GET['id_registro']) ? $_GET['id_registro'] : "";
        
        $obj = new Conect();
        $con = $obj->getCon();
        $consulta = "SELECT a.fecha_solicitud,a.folio,proveedor,
        (SELECT CONCAT(z.id_ur,' - ',z.nombre_ur) FROM cat_ur z WHERE a.ur_sol=z.id_ur) AS UR,
        a.nombre_sol,(SELECT z.nombre_sede from cat_sedes z WHERE z.id_sede=a.id_sede) AS SEDE,
        (SELECT descripcion FROM catalogo z WHERE z.id_cat=a.id_tipo_servicio) AS TIPO_SERVICIO,
        (SELECT descripcion FROM catalogo z WHERE z.id_cat=b.id_tipo_mantenimiento) AS TIPO_MANTENIMIENTO,
        a.detalle_ubicacion_sol,
        a.desc_reporte,
        b.desc_actividad,
        b.fecha_inicio,
        b.fecha_fin,
        (SELECT descripcion FROM catalogo z WHERE z.id_cat=b.id_contrato) AS CONTRATO,
        b.material,
        b.observaciones,
        (SELECT CONCAT(z.nombre,' ',z.apellido1) from cat_responsable z WHERE z.id_responsable=a.id_turnado) AS RESPONSABLE
        FROM reporte a, atencion b
        WHERE a.id_reporte=b.id_registro 
        AND a.id_reporte=?";
        $stmt = mysqli_prepare($con,$consulta);
        mysqli_stmt_bind_param($stmt,'i', $id_registro);
        /* ejecuta sentencias preparadas */
        mysqli_stmt_execute($stmt);
        $rs = mysqli_stmt_get_result($stmt);
        $obj->closeCon($con);

        $data  = $rs->fetch_array(MYSQLI_ASSOC);

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        $img_file = '../images/fondo.png';
        $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Establecer información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Dirección General de Recursos Materiales');
$pdf->SetTitle('Hoja de atención');
$pdf->SetSubject('Buzón de Solicitud de Servicios');
$pdf->SetKeywords('PDF, DGRM, SICT');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// remove default footer
$pdf->setPrintFooter(false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------


// add a page
$pdf->AddPage();
$fecha_actual  = date('d')."/".date('m')."/".date('Y');
$contrato = ($data["CONTRATO"] === null?"N/A":$data["CONTRATO"]);
$material = ($data["material"] === null?"N/A":$data["material"]);
$observaciones = ($data["observaciones"] === null || $data["observaciones"] === "" ?"Ninguna":$data["observaciones"]);
$texto_proveedor = ($data["proveedor"] === 1?" del proveedor":"");
// Print a text
$html = '
<style>
td {
    vertical-align: center;
    text-align:center;
    height: 30px;
  }
</style>

<br/><br/><br/><br/>
<span style="font-family:Montserrat-Bold; font-weight:bold; font-size:10pt; text-align:right; margin: 0; line-height: 1;">Fecha de impresión: '.$fecha_actual.'</span>
<br/>
<span style="font-family:Montserrat-Bold; font-weight:bold; font-size:10pt; text-align:right; margin: 0; line-height: 1;">Folio: '.$data["folio"].'</span>
<h2 style="font-family:Montserrat-SemiBold; font-weight:bold; text-align:center">ORDEN DE SERVICIO</h2>
<table border="1" >
<tr>
<td colspan="4" style="text-align:center;"><h3>DATOS DE LA SOLICITUD</h3></td>
</tr>  
<tr>
    <td><b>Fecha solicitud:</b></td>
    <td colspan="4" >'.$data["fecha_solicitud"].'</td>
  </tr>
  <tr>
    <td><b>Unidad Administrativa solicitante:</b></td>
    <td colspan="3">'.$data["UR"].'</td>
  </tr>
  <tr>
  <td><b>Edificio donde se suscitó el reporte:</b></td>
  <td >'.$data["SEDE"].'</td>
  <td><b>Ubicación específica:</b></td>
  <td >'.$data["detalle_ubicacion_sol"].'</td> 
  </tr> 
  <tr>
    <td><b>Tipo de servicio:</b></td>
    <td colspan="3">'.$data["TIPO_SERVICIO"].'</td>
  </tr>  
    <tr>
    <td><b>Tipo de mantenimiento:</b></td>
    <td colspan="3">'.$data["TIPO_MANTENIMIENTO"].'</td>
    </tr>
<tr>
  <td ><b>Descripción del reporte:</b></td>
  <td colspan="3" style="height: 40px;">'.$data["desc_reporte"].'</td>
</tr>
<tr>
<td colspan="4" ><h3>DATOS DE LA ATENCIÓN</h3></td>
</tr>
<tr>
<td><b>Actividad realizada:</b></td>
<td colspan="3" style="height: 40px;">'.$data["desc_actividad"].'</td>
</tr>
<tr>
<td><b>Fecha de inicio:</b></td>
<td>'.$data["fecha_inicio"].'</td>
<td><b>Fecha fin:</b></td>
<td >'.$data["fecha_fin"].'</td>
</tr>
<tr>
<td><b>Contrato utilizado:</b></td>
<td colspan="3" >'.$contrato.'</td>
</tr>
<tr>
<td><b>Material propio utilizado:</b></td>
<td colspan="3" style="height: 40px;" >'.$material.'</td>
</tr>
<tr>
<td><b>Observaciones:</b></td>
<td colspan="3" style="height: 40px;">'.$observaciones.'</td>
</tr>
</table>
<br/><br/>
<table border="1" >
<tr>
<td><h4>ATENDIÓ</h4></td>
<td><h4>SUPERVISÓ</h4></td>
<td><h4>ACEPTÓ</h4></td>
</tr> 
<tr>
<td style="height: 80px;"></td>
<td style="height: 80px;"></td>
<td style="height: 80px;"></td>
</tr>
<tr>
<td>Nombre y firma</td>
<td >Nombre y firma</td>
<td >Nombre y firma</td>
</tr>  
</table>

';

$pdf->writeHTML($html, true, false, true, false, '');


// Cerrar y generar el PDF
$pdf->Output('ejemplo.pdf', 'I'); // Cambiar 'I' a 'D' si deseas descargar el PDF en lugar de verlo en el navegador