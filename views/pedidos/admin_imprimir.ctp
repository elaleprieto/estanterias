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
$tcpdf -> SetHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

# Pie de Página
$tcpdf -> SetFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
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
$celda_ancho_detalle = 162;
$celda_ancho_transporte = 98;

# Alto de celda (en milímetros)
$celda_alto = 6;

###############################################################
# Fila de Transporte
###############################################################

# Cell ($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
$tcpdf -> Cell($celda_ancho_transporte, $celda_alto, 'Transporte: ' . $pedido['Transporte']['nombre'], 0, 0, '');
if ($pedido['Pedido']['contrarrembolso'] && $pedido['Pedido']['cobinpro']) {
	$celda_ancho_transporte /= 3;
	$tcpdf -> Cell($celda_ancho_transporte, $celda_alto, 'Contrarrembolso', 0, 0, 'L');
	$tcpdf -> Cell($celda_ancho_transporte, $celda_alto, 'Cobinpro', 0, 0, 'L');
} else if ($pedido['Pedido']['contrarrembolso'] || $pedido['Pedido']['cobinpro']) {
	$celda_ancho_transporte /= 2;
	if ($pedido['Pedido']['contrarrembolso']) {
		$tcpdf -> Cell($celda_ancho_transporte, $celda_alto, 'Contrarrembolso', 0, 0, 'L');
	} else {
		$tcpdf -> Cell($celda_ancho_transporte, $celda_alto, 'Cobinpro', 0, 0, 'L');
	}
}
if ($pedido['Pedido']['b']) {
	$tcpdf -> Cell($celda_ancho_transporte, $celda_alto, '***', 0, 0, 'R');
}
$tcpdf -> Cell(1, $celda_alto, '', 0, 1, '');
###############################################################
# Fila de títulos de columna
###############################################################
$tcpdf -> Cell($celda_ancho_codigo, $celda_alto, '', 1, 0, 'C');
$tcpdf -> Cell($celda_ancho_codigo, $celda_alto, 'Cód', 1, 0, 'C');
$tcpdf -> Cell($celda_ancho_cantidad, $celda_alto, 'Cant', 1, 0, 'C');
$tcpdf -> Cell($celda_ancho_unidad, $celda_alto, 'Unid', 1, 0, 'C');
$tcpdf -> Cell($celda_ancho_detalle, $celda_alto, 'Detalle', 1, 1, 'C');

###############################################################
# Armo la lista
###############################################################

# Tamaño y Tipo de Letra
$tcpdf -> SetFont("freesans", "", 10);

# Cell ($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
//print_r($usuarios);
foreach ($ordenes as $key => $orden) {
	if ($orden[0]["orden_estado"]) {
		$tcpdf -> Cell($celda_ancho_codigo, $celda_alto, "X", 1, 0, 'C');
		if ($orden[0]["sin_cargo"]) {
			$tcpdf -> SetFont("freesans", "", 9);
			$tcpdf -> Cell($celda_ancho_codigo, $celda_alto, 'Sin Cargo', 1, 0, 'C', '', '', 2);
			$tcpdf -> SetFont("freesans", "", 10);
		} else {
			$tcpdf -> Cell($celda_ancho_codigo, $celda_alto, $orden[0]["id"], 1, 0, 'C');
		}
	} else {
		$tcpdf -> Cell($celda_ancho_codigo, $celda_alto, "--", 1, 0, 'C');
		$tcpdf -> Cell($celda_ancho_codigo, $celda_alto, "", 1, 0, 'C');
	}
	$tcpdf -> Cell($celda_ancho_cantidad, $celda_alto, $orden[0]["cantidad"], 1, 0, 'C');
	$tcpdf -> Cell($celda_ancho_unidad, $celda_alto, $orden[0]["unidad"], 1, 0, 'C');
	$tcpdf -> Cell($celda_ancho_detalle, $celda_alto, $orden[0]["detalle"], 1, 1, 'L');
}

$fecha = date('Y-m-d H.i', strtotime($pedido['Pedido']['created']));

$tcpdf -> Output($pedido['Cliente']['nombre'] . " - " . $fecha . ".pdf", "I");
?>