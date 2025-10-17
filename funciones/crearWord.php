<?php

require_once "../vendor/autoload.php";
use PhpOffice\PhpWord\Style\Language;

$phpWord = new PhpOffice\PhpWord\PhpWord();

//Cambiar el idioma del documento
$phpWord->getSettings()->setThemeFontLang(new Language("es-ES"));

$nombreEmpresa = 'prueba2';

$estiloVertical = [
    "borderBottomColor" => "#000000",
    "borderLeftColor" => "#000000",
    "borderRightColor" => "#000000",
    "borderTopColor" => "#000000",
    "orientation" => "portrait",
    "borderTopSize" => "20000",
    "pageSizeW" => "", 

];

$estiloCabecera = array(
    'borderColor' => '8fd247',
    'borderSize'  => 6,
    'cellMargin'  => 100,
    'cellSpacing' => 200
);

$estiloImagenCabecera = [
    "height" => 50,
    "width" => 150,
];

$prueba = array(
    'borderColor' => '000000',
    'borderSize'  => 6,

);

$seccion = $phpWord->addSection();
$seccionLateral = $phpWord->addSection($estiloVertical);

$fuenteLateral = [];

$paragrafoLateral = [];


//Cabecera DIXMA
//$tablaCabecera = $seccion->addTable($estiloCabecera);
//$tablaCabecera->addRow();
//$celda = $tablaCabecera->addCell(10000, array(
//
//    'bgColor' => '8fd247',
//    'borderRightColor' => '000000',
//    'gridSpan' => 4,
//    'valign' => 'top'
//
//));
//
//$celda->addImage("../images/logoWord.jpg");
//$celda->addText('Consultor:', array( 'allCaps' => true, ), array( 'alignment' => 'end', 'textAlignment' => 'top'));

$seccion->addText("Datos de la empresa (deudor)");

$tablaDatosEmpresa = $seccion->addTable();
$tablaDatosEmpresa->addRow();

$tablaDatosEmpresa->addCell()->addText('Razón social:');
$tablaDatosEmpresa->addCell()->addText('CIF:');

$tablaDatosEmpresa->addRow();

$tablaDatosEmpresa->addCell()->addText('Domicilio social:');

$tablaDatosEmpresa->addRow();

$tablaDatosEmpresa->addCell()->addText('Población:');
$tablaDatosEmpresa->addCell()->addText('Código postal:');
$tablaDatosEmpresa->addCell()->addText('Provincia:');



if(!file_exists("../WORDS")){

    mkdir("../WORDS");

}

$URLDocumento = "../WORDS/" . $nombreEmpresa . ".docx";                         
$crearDocumento = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$crearDocumento->save($URLDocumento);

echo "Documento creado con EXITO <br>";

var_dump($URLDocumento);

descargarArchivo($URLDocumento);

function descargarArchivo($ruta){

    header('Content-Disposition: attachment; filename="' . $ruta . '" ');
    header("Content-type: application/msword");
    readfile($ruta);


}



?>