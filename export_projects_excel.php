<?php
require 'vendor/autoload.php'; // חובה לוודא שהותקן phpoffice/phpspreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$conn = new mysqli("localhost", "root", "", "kablanadmin");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$query = "SELECT * FROM projects ORDER BY id DESC";
$result = $conn->query($query);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Projects");

// כותרות
$sheet->fromArray(["#","שם הפרויקט","כתובת","קו רוחב","קו אורך","התחלה","סיום","סטטוס"], NULL, 'A1');

// נתונים
$rowNum = 2;
while ($row = $result->fetch_assoc()) {
    $sheet->fromArray([
        $row['id'],
        $row['name'],
        $row['address'],
        $row['latitude'],
        $row['longitude'],
        $row['start_date'],
        $row['end_date'],
        $row['status']
    ], NULL, 'A'.$rowNum);
    $rowNum++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="projects.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
