<?php

// Incluir librería TCPDF 
require_once('../scripts/library/tcpdf.php');

// Obtener contenido HTML generado
$html = ob_get_contents();

// Crear DOMDocument  
$doc = new DOMDocument();

// Cargar HTML
$doc->loadHTML($html);
      
// Obtener tabla
$table = $doc->getElementById('tablaCompra');

// Iniciar buffer para ir agregando datos
$content = ''; 

// Agregar título y encabezado de tabla
$content .= '<h3 align="center">Lista de Compras</h3>';
$content .= '<table border="1" cellpadding="5">'; 
$content .= '<tr><th>Artículo</th><th>Cant.</th><th>V. Unitario</th><th>V. Total</th></tr>';

// Obtener rows  
$rows = $table->getElementsByTagName('tr');

// Recorrer rows
foreach ($rows as $row) {

  // Obtener celdas 
  $cells = $row->getElementsByTagName('td');
  
  // Iniciar fila
  $content .= '<tr>';

  // Recorrer celdas
  foreach ($cells as $cell) {
   
    // Agregar celda 
    $content .= '<td>' . $cell->textContent . '</td>';

  }

  // Cerrar fila
  $content .= '</tr>';
  
}

// Cerrar tabla
$content .= '</table>';

// Crear instancia PDF 
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// ... (configurar metadata PDF)

// Agregar página
$pdf->AddPage(); 

// Escribir contenido al PDF
$pdf->writeHTML($content);  

// Forzar descarga 
$pdf->Output('lista_compras.pdf', 'D');

?>