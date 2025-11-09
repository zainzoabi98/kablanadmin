<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$id = intval($_GET['id'] ?? 0);
$conn = new mysqli("localhost", "root", "", "kablanadmin");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$result = $conn->query("SELECT * FROM projects WHERE id=$id LIMIT 1");
if (!$result || $result->num_rows == 0) die("Project not found");

$row = $result->fetch_assoc();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Project #".$id);

$sheet->fromArray(["שדה","ערך"], NULL, 'A1');
$data = [
    ["מזהה",$row['id']],
    ["שם הפרויקט",$row['name']],
    ["כתובת",$row['address']],
    ["קו רוחב",$row['latitude']],
    ["קו אורך",$row['longitude']],
    ["תאריך התחלה",$row['start_date']],
    ["תאריך סיום",$row['end_date']],
    ["סטטוס",$row['status']]
];
$sheet->fromArray($data, NULL, 'A2');

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=project_{$id}.xlsx");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
