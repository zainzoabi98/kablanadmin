<?php
// חיבור למסד הנתונים
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kablanadmin";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// נתונים לסיכום מהיר
$projects  = $conn->query("SELECT COUNT(*) AS total FROM projects")->fetch_assoc()['total'] ?? 0;
$workers   = $conn->query("SELECT COUNT(*) AS total FROM workers")->fetch_assoc()['total'] ?? 0;
$tasks     = $conn->query("SELECT COUNT(*) AS total FROM tasks")->fetch_assoc()['total'] ?? 0;
$materials = $conn->query("SELECT COUNT(*) AS total FROM materials_library")->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="he">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>KABLAN | מערכת ניהול קבלנים</title>
<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background-color: #f4f6f9;
    display: flex;
    direction: rtl;
}

/* Sidebar */
.sidebar {
    width: 250px;
    background-color: #0d47a1;
    color: white;
    height: 100vh;
    position: fixed;
    top: 0;
    right: 0; /* הצד הנכון לעברית */
    padding-top: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.logo {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    margin-bottom: 30px;
    text-align: center;
}

.logo-icon {
    width: 80px;
    height: 80px;
    object-fit: contain;
    border-radius: 10px;
    background-color: #ffffff22;
    padding: 5px;
}

.logo h2 {
    font-size: 22px;
    margin: 0;
    color: #fff;
}

.sidebar a {
    display: block;
    width: 100%;
    padding: 12px 20px;
    text-decoration: none;
    color: white;
    font-size: 16px;
    transition: background 0.3s;
    text-align: right;
}

.sidebar a:hover,
.sidebar a.active {
    background-color: #1565c0;
}

/* Main Content */
.main {
    margin-right: 250px;
    padding: 30px;
    width: calc(100% - 250px);
}

.header {
    background-color: white;
    padding: 20px 30px;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header h1 {
    color: #003366;
    font-size: 24px;
    margin: 0;
}

/* Cards */
.cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
    gap: 20px;
}

.card {
    background-color: white;
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}

.card .icon {
    font-size: 40px;
    margin-bottom: 10px;
    color: #0074e4;
}

.card h3 {
    margin-bottom: 8px;
    color: #003366;
    font-size: 18px;
}

.card p {
    font-size: 22px;
    font-weight: bold;
    color: #333;
    margin: 0;
}
</style>
</head>
<body>

<!-- סיידבר -->
<div class="sidebar">
    <div class="logo">
        <img src="assets/kablanlogo.gif" alt="KABLAN Logo" class="logo-icon">
        <h2>KABLAN</h2>
    </div>
    <a href="dashboard.php" class="active">🏠 דף הבית</a>
    <a href="projects.php">🏗️ פרויקטים</a>
    <a href="workers.php">👷 עובדים</a>
    <a href="tasks.php">🧰 משימות</a>
    <a href="materials.php">📦 חומרים</a>
    <a href="vehicles.php">🚗 רכבים</a>
    <a href="reports.php">📊 דוחות</a>
    <a href="settings.php">⚙️ הגדרות</a>
</div>

<!-- תוכן ראשי -->
<div class="main">
    <div class="header">
        <h1>ברוך הבא למערכת KABLAN</h1>
        <div>📅 <?php echo date("d/m/Y"); ?></div>
    </div>

    <div class="cards">
        <div class="card">
            <div class="icon">🏗️</div>
            <h3>סה"כ פרויקטים</h3>
            <p><?php echo $projects; ?></p>
        </div>

        <div class="card">
            <div class="icon">👷</div>
            <h3>סה"כ עובדים</h3>
            <p><?php echo $workers; ?></p>
        </div>

        <div class="card">
            <div class="icon">🧰</div>
            <h3>סה"כ משימות</h3>
            <p><?php echo $tasks; ?></p>
        </div>

        <div class="card">
            <div class="icon">📦</div>
            <h3>חומרי מלאי</h3>
            <p><?php echo $materials; ?></p>
        </div>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>
