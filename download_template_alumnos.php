<?php
$vendor = __DIR__ . '/vendor/autoload.php';
if (!file_exists($vendor)) {
    // Fallback: output CSV if PhpSpreadsheet (vendor) is not available
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="template_alumnos.csv"');
    // CSV fallback contains all single-insert fields (without IdEmpresa)
    echo "Apellidos,Nombre,Telefono,Email,Fecha_Nacimiento,NIF,NumeroSeguridadSocial,CategoriaProfesional,Colectivo,GrupoCotizacion,NivelEstudios,CosteHora,HorarioLaboral,Sexo,Discapacidad\n";
    echo "García,Juan,600123456,j.garcia@example.com,1985-04-12,12345678A,,tecnico,regimenGeneral,(1) Ingenieros y licenciados,tecnicoSuperior,15.00,09:00-17:00,hombre,No\n";
    echo "López,María,600654321,m.lopez@example.com,1990-11-05,87654321B,,trabajadorCualificado,fijoDiscontinuo,(2) Ingenieros técnicos,educacionPostsecundaria,12.50,08:00-16:00,mujer,No\n";
    exit;
}
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Alumnos');

// ── Header ──────────────────────────────────────────────────────────────────
$headers = ['Apellidos','Nombre','Telefono','Email','Fecha_Nacimiento','NIF','NumeroSeguridadSocial','CategoriaProfesional','Colectivo','GrupoCotizacion','NivelEstudios','CosteHora','HorarioLaboral','Sexo','Discapacidad'];
foreach ($headers as $i => $h) {
    $col = chr(65 + $i); // A, B, C …
    $sheet->setCellValue($col . '1', $h);
    $sheet->getColumnDimension($col)->setWidth(22);
}

// Stile header: sfondo verde, testo bold
$sheet->getStyle('A1:F1')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '3a7d44']],
]);

// ── Righe di esempio ─────────────────────────────────────────────────────────
$sheet->fromArray(['García','Juan','600123456','j.garcia@example.com','1985-04-12','12345678A','','tecnico','regimenGeneral','(1) Ingenieros y licenciados','tecnicoSuperior','15.00','09:00-17:00','hombre','No'], null, 'A2');
$sheet->fromArray(['López','María','600654321','m.lopez@example.com','1990-11-05','87654321B','','trabajadorCualificado','fijoDiscontinuo','(2) Ingenieros técnicos','educacionPostsecundaria','12.50','08:00-16:00','mujer','No'],  null, 'A3');

// ── Download ─────────────────────────────────────────────────────────────────
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="template_alumnos.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
