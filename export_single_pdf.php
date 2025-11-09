<?php
require_once('tcpdf/tcpdf.php');
$id = intval($_GET['id'] ?? 0);
$conn = new mysqli("localhost", "root", "", "kablanadmin");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$result = $conn->query("SELECT * FROM projects WHERE id=$id LIMIT 1");
if (!$result || $result->num_rows == 0) die("Project not found");
$row = $result->fetch_assoc();

$pdf = new TCPDF();
$pdf->SetCreator('KABLAN');
$pdf->SetTitle('דוח פרויקט יחיד');
$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 12);

$html = '<h2 style="color:#003366;">📄 דו"ח פרויקט #'.$row['id'].'</h2>';
$html .= '<table border="1" cellpadding="6">
<tr><th>שדה</th><th>ערך</th></tr>
<tr><td>שם</td><td>'.$row['name'].'</td></tr>
<tr><td>כתובת</td><td>'.$row['address'].'</td></tr>
<tr><td>קו רוחב</td><td>'.$row['latitude'].'</td></tr>
<tr><td>קו אורך</td><td>'.$row['longitude'].'</td></tr>
<tr><td>תאריך התחלה</td><td>'.$row['start_date'].'</td></tr>
<tr><td>תאריך סיום</td><td>'.$row['end_date'].'</td></tr>
<tr><td>סטטוס</td><td>'.$row['status'].'</td></tr>
</table>';

$pdf->writeHTML($html);
$pdf->Output("project_{$id}.pdf", 'I');
exit;
?>
