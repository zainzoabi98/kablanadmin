<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kablanadmin";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// ✅ הוספת פרויקט חדש
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_project"])) {
    $tenant_id = 1;
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;
    $status = $_POST['status'] ?? 'active';

    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO projects (tenant_id, name, address, latitude, longitude, start_date, end_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issddsss", $tenant_id, $name, $address, $latitude, $longitude, $start_date, $end_date, $status);
        $stmt->execute();
        $message = "✅ הפרויקט נוסף בהצלחה!";
        $stmt->close();
    } else {
        $message = "⚠️ אנא מלא לפחות את שם הפרויקט.";
    }
}

// ✅ שינוי סטטוס
if (isset($_GET['update_status'])) {
    $id = intval($_GET['update_status']);
    $new_status = $_GET['status'] ?? 'active';
    $conn->query("UPDATE projects SET status='$new_status' WHERE id=$id");
    header("Location: projects.php");
    exit();
}

// ✅ שליפת נתונים
$result = $conn->query("SELECT * FROM projects ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="he">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>KABLAN | ניהול פרויקטים</title>
<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        background-color: #f5f7fa;
        display: flex;
    }

    .sidebar {
        width: 250px;
        background: linear-gradient(180deg, #003366, #00509e);
        color: white;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        padding-top: 30px;
        box-shadow: 2px 0 10px rgba(0,0,0,0.2);
    }
    .sidebar h2 {
        text-align: center;
        margin-bottom: 30px;
    }
    .sidebar a {
        display: block;
        color: white;
        text-decoration: none;
        padding: 14px 20px;
        border-left: 4px solid transparent;
        transition: 0.3s;
    }
    .sidebar a:hover {
        background: #0074e4;
        border-left: 4px solid #fff;
    }

    .main {
        margin-left: 250px;
        padding: 30px;
        width: calc(100% - 250px);
    }

    h1 {
        color: #003366;
        margin-bottom: 20px;
    }

    .message {
        background-color: #e0f3ff;
        border-left: 5px solid #0074e4;
        padding: 10px;
        margin-bottom: 20px;
    }

    form {
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    input, select, button {
        padding: 10px;
        margin: 5px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
    }

    button {
        background-color: #0074e4;
        color: white;
        cursor: pointer;
        transition: 0.3s;
    }

    button:hover {
        background-color: #005bb5;
    }

    .export-buttons {
        margin-bottom: 15px;
    }
    .export-buttons button {
        margin-right: 10px;
        background-color: #28a745;
    }
    .export-buttons button.pdf {
        background-color: #dc3545;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    table th, table td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    table th {
        background-color: #0074e4;
        color: white;
    }

    .status {
        padding: 6px 12px;
        border-radius: 6px;
        color: white;
    }
    .active { background-color: #28a745; }
    .on_hold { background-color: #ffc107; color: black; }
    .completed { background-color: #007bff; }

    .action-btn {
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
        color: white;
        font-size: 13px;
    }
    .edit { background: #007bff; }
    .delete { background: #dc3545; }
    .excel { background: #28a745; }
    .pdf { background: #6f42c1; }
</style>
</head>
<body>

<div class="sidebar">
    <h2>KABLAN</h2>
    <a href="dashboard.php">🏠 דף הבית</a>
    <a href="projects.php" style="background:#0074e4;">🏗️ פרויקטים</a>
    <a href="workers.php">👷 עובדים</a>
    <a href="tasks.php">🧰 משימות</a>
    <a href="materials.php">📦 חומרים</a>
    <a href="vehicles.php">🚗 רכבים</a>
    <a href="reports.php">📊 דוחות</a>
</div>

<div class="main">
    <h1>ניהול פרויקטים</h1>

    <?php if($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="name" placeholder="שם הפרויקט" required>
        <input type="text" name="address" placeholder="כתובת האתר">
        <input type="text" name="latitude" placeholder="קו רוחב (Latitude)">
        <input type="text" name="longitude" placeholder="קו אורך (Longitude)">
        <label>תאריך התחלה:</label>
        <input type="date" name="start_date">
        <label>תאריך סיום:</label>
        <input type="date" name="end_date">
        <select name="status">
            <option value="active">פעיל</option>
            <option value="on_hold">בהמתנה</option>
            <option value="completed">הושלם</option>
        </select>
        <button type="submit" name="add_project">➕ הוסף פרויקט</button>
    </form>

    <div class="export-buttons">
        <button onclick="window.location.href='export_projects_excel.php'">📊 ייצוא כללי לאקסל</button>
        <button class="pdf" onclick="window.location.href='export_projects_pdf.php'">📄 ייצוא כללי ל-PDF</button>
    </div>

    <table>
        <tr>
            <th>#</th>
            <th>שם</th>
            <th>כתובת</th>
            <th>מיקום</th>
            <th>תאריכים</th>
            <th>סטטוס</th>
            <th>עדכון</th>
            <th>פעולות</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['address'] ?? '-') ?></td>
            <td><?= ($row['latitude'] && $row['longitude']) ? $row['latitude'].", ".$row['longitude'] : '-' ?></td>
            <td><?= $row['start_date']." - ".$row['end_date'] ?></td>
            <td><span class="status <?= $row['status'] ?>"><?= htmlspecialchars($row['status']) ?></span></td>
            <td>
                <form method="get" style="display:inline;">
                    <input type="hidden" name="update_status" value="<?= $row['id'] ?>">
                    <select name="status" onchange="this.form.submit()">
                        <option value="active" <?= $row['status']=='active'?'selected':'' ?>>פעיל</option>
                        <option value="on_hold" <?= $row['status']=='on_hold'?'selected':'' ?>>בהמתנה</option>
                        <option value="completed" <?= $row['status']=='completed'?'selected':'' ?>>הושלם</option>
                    </select>
                </form>
            </td>
            <td>
                <button class="action-btn edit">✏️ ערוך</button>
                <button class="action-btn delete">🗑️ מחק</button>
                <button class="action-btn excel" onclick="window.location.href='export_single_excel.php?id=<?= $row['id'] ?>'">📊</button>
                <button class="action-btn pdf" onclick="window.location.href='export_single_pdf.php?id=<?= $row['id'] ?>'">📄</button>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>

<?php $conn->close(); ?>
