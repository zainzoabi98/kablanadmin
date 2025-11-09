<?php
require_once('tcpdf/tcpdf.php');
$conn = new mysqli("localhost", "root", "", "kablanadmin");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$result = $conn->query("SELECT * FROM projects ORDER BY id DESC");

// צור PDF
$pdf = new TCPDF();
$pdf->SetCreator('KABLAN');
$pdf->SetAuthor('KABLAN SYSTEM');
$pdf->SetTitle('דוח פרויקטים');
$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 11);

$html = '<h2 style="color:#003366;">🧱 מערכת KABLAN - דוח כל הפרויקטים</h2>';
$html .= '<table border="1" cellpadding="6">
<tr style="background-color:#0074e4;color:white;">
<th width="5%">#</th><th width="20%">שם</th><th width="25%">כתובת</th>
<th width="20%">תאריכים</th><th width="15%">סטטוס</th>
</tr>';

while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
        <td>'.$row['id'].'</td>
        <td>'.htmlspecialchars($row['name']).'</td>
        <td>'.htmlspecialchars($row['address']).'</td>
        <td>'.$row['start_date'].' - '.$row['end_date'].'</td>
        <td>'.$row['status'].'</td>
    </tr>';
}
$html .= '</table>';

$pdf->writeHTML($html);
$pdf->Output('projects_report.pdf', 'I');
exit;
?>
