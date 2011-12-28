<?php
# Tamaño de una Página A4 común y silvestre
# ancho = 210 [mm]
# alto 	= 297 [mm]

App::import('Lib', 'tcpdf/tcpdf');
$tcpdf = new TCPDF();

$textfont = 'freesans';
$tcpdf -> SetCreator(PDF_CREATOR);
$tcpdf -> SetAuthor("autor");
$tcpdf -> SetTitle("Título");
$tcpdf -> SetSubject("Tutorial TCPDF en cakePHP");
$tcpdf -> SetKeywords("TCPDF, PDF, cakePHP, ejemplo");
$tcpdf -> SetPrintHeader(TRUE);
$tcpdf -> SetPrintFooter(TRUE);
$tcpdf -> SetImageScale(PDF_IMAGE_SCALE_RATIO);
$tcpdf -> AliasNbPages();

# Márgenes de la página
// $tcpdf -> SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$tcpdf -> SetAutoPageBreak(TRUE, 7);
// $tcpdf -> SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$tcpdf -> SetMargins(7, 12, 7);
// $tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER); en milímetros
$tcpdf -> SetHeaderMargin(7);
// $tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER); en milímetros
$tcpdf -> SetFooterMargin(7);

/*	if(file_exists(K_PATH_IMAGES . 'alpha.png')) {
 echo "The file exists";
 } else {
 echo "The file does not exist";
 }
 */
# 	Image ($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array())
// $tcpdf -> Image(K_PATH_IMAGES . 'logo.gif', 15, 15, 0, 0, 'GIF');

# Encabezado de Página
$tcpdf -> SetHeaderData("", "", "Pedido de " . $pedido['Cliente']['nombre'], "");
$tcpdf -> SetHeaderFont(Array(
		PDF_FONT_NAME_MAIN,
		'',
		PDF_FONT_SIZE_MAIN
));

# Pie de Página
$tcpdf -> SetFooterFont(Array(
		PDF_FONT_NAME_DATA,
		'',
		PDF_FONT_SIZE_DATA
));
$tcpdf -> SetFooterMargin(7);

$tcpdf -> AddPage();

# Titulo
// $tcpdf -> SetFont("freesans", "B", 20);
// $tcpdf -> Cell(0, 15, "Pedido de " . $pedido['Cliente']['nombre'], 0, 1, 'C');

###############################################################
# Seteo de parámetros
###############################################################

# Tamaño y Tipo de Letra
$tcpdf -> SetFont("freesans", "B", 9);

# Ancho de celda (en milímetros)
$celda_ancho = 90;
$celda_ancho_codigo = 9;
$celda_ancho_cantidad = 8;
$celda_ancho_unidad = 8;
$celda_ancho_detalle = 137;
$celda_ancho_observaciones = 25;
$fila_ancho_transporte = 171;
$celda_ancho_b = 5;
$celda_ancho_cobinpro = 20;
$datos_fila_transporte = 3;
$celda_ancho_transporte = $fila_ancho_transporte / $datos_fila_transporte;

# Alto de celda (en milímetros)
$celda_alto = 6;

###############################################################
# Fila de Transporte
###############################################################
# Cell ($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

# Se imprime el Transporte
$tcpdf -> Cell($celda_ancho_transporte, $celda_alto, 'Transporte: ' . $pedido['Transporte']['nombre'], 0, 0, '');
# Se imprime la Cantidad de Artículos
$tcpdf -> Cell($celda_ancho_transporte, $celda_alto, 'Cantidad de Artículos: '. sizeof($ordenes), 0, 0, 'C');
# Si se envía Contrarrembolso, se imprime... si no, se imprime en blanco.
$pedido['Pedido']['contrarrembolso'] ? $tcpdf -> Cell($celda_ancho_transporte, $celda_alto, 'Contrarrembolso', 0, 0, 'C') : $tcpdf -> Cell($celda_ancho_transporte, $celda_alto, '', 0, 0, 'L');
# Si se envía Cobinpro, se imprime... si no, se imprime en blanco.
$pedido['Pedido']['cobinpro'] ? $tcpdf -> Cell($celda_ancho_cobinpro, $celda_alto, 'Cobinpro', 0, 0, 'C') : $tcpdf -> Cell($celda_ancho_cobinpro, $celda_alto, '', 0, 0, 'L');

if ($pedido['Pedido']['b']) {
	$tcpdf -> Cell($celda_ancho_b, $celda_alto, '***', 0, 0, 'R');
}
# Se imprime la celda que provoca el salto de línea
$tcpdf -> Cell(1, $celda_alto, '', 0, 1, '');

###############################################################
# Fila de Observaciones
###############################################################
# TCPDF::MultiCell($w, $h, $txt, $border=0,	$align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0,$valign='T', $fitcell=false)
if ($pedido['Pedido']['observaciones']) {
	$tcpdf -> Cell(0, $celda_alto, "Observaciones: ", array('LTR' => 1), 1);

	# Tamaño y Tipo de Letra
	$tcpdf -> SetFont("freesans", "", 9);

	$tcpdf -> MultiCell(0, $celda_alto, $pedido['Pedido']['observaciones'], array('LR' => 1), 'L');

	# Tamaño y Tipo de Letra
	$tcpdf -> SetFont("freesans", "B", 9);
}

###############################################################
# Fila de títulos de columna
###############################################################
$tcpdf -> Cell($celda_ancho_codigo, $celda_alto, '', 1, 0, 'C');
$tcpdf -> Cell($celda_ancho_codigo, $celda_alto, 'Cód', 1, 0, 'C');
$tcpdf -> Cell($celda_ancho_cantidad, $celda_alto, 'Cant', 1, 0, 'C');
$tcpdf -> Cell($celda_ancho_unidad, $celda_alto, 'Unid', 1, 0, 'C');
$tcpdf -> Cell($celda_ancho_detalle, $celda_alto, 'Detalle', 1, 0, 'C');
$tcpdf -> Cell($celda_ancho_observaciones, $celda_alto, 'Observaciones', 1, 1, 'C');

###############################################################
# Armo la lista
###############################################################

# Tamaño y Tipo de Letra
$tcpdf -> SetFont("freesans", "", 10);

$html = '<table cellspacing="0" cellpadding="1" border="0.7">';
foreach ($ordenes as $key => $orden) {
	$html .= "<tr>"; 
	if ($orden[0]["orden_estado"]) {
		$html .= '<td width="'.$celda_ancho_codigo.'mm" height="'.$celda_alto.'mm" align="center">X</td>';
		
		if ($orden[0]["sin_cargo"]) {
			$html .= '<td width="'.$celda_ancho_codigo.'mm" height="'.$celda_alto.'mm" align="center"><font size="8">Sin Cargo</font></td>';
		} else {
			$html .= '<td width="'.$celda_ancho_codigo.'mm" height="'.$celda_alto.'mm" align="center"><font size="9">'.$orden[0]["id"].'</font></td>';
		}
	} else {
		$html .= '<td width="'.$celda_ancho_codigo.'mm" height="'.$celda_alto.'mm" align="center">--</td>';
		$html .= '<td width="'.$celda_ancho_codigo.'mm" height="'.$celda_alto.'mm"></td>';
	}
		$html .= '<td width="'.$celda_ancho_cantidad.'mm" height="'.$celda_alto.'mm" align="center">'.$orden[0]["cantidad"].'</td>';
		$html .= '<td width="'.$celda_ancho_unidad.'mm" height="'.$celda_alto.'mm" align="center"><font size="8">'.$orden[0]["unidad"].'</font></td>';
		$html .= '<td width="'.$celda_ancho_detalle.'mm" height="'.$celda_alto.'mm">'.$orden[0]["detalle"].'</td>';
		
		$html .= '<td width="'.$celda_ancho_observaciones.'mm" height="'.$celda_alto.'mm"><font size="8">'.$orden[0]["observaciones"].'</font></td>';

	$html .= "</tr>";
}
$html .= '</table>';

// output the HTML content
$tcpdf -> writeHTML($html);

$fecha = date('Y-m-d H.i', strtotime($pedido['Pedido']['finalizado']));

$tcpdf -> Output($pedido['Cliente']['nombre'] . " - " . $fecha . ".pdf", "I");
?>