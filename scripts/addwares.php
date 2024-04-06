<?php
// Подключение к базе данных
session_start();

if ($_SESSION["position"] != "accountant") {
    header("Location: http://localhost/bank/index.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "course";

$conn = new mysqli($servername, $username, $password, $dbname);

mysqli_set_charset($conn, "utf8");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Получение данных от пользователя
$pname = $_POST['pname'];
$cid = $_POST['cid'];
$arrdate = $_POST['arrdate'];
$amt = $_POST['amt'];

// Проверка наличия записи с указанными значениями pname и cid
$checkStmt = $conn->prepare("SELECT pname, cid, amt FROM bank WHERE pname = ? AND cid = ?");
$checkStmt->bind_param("ss", $pname, $cid);
$checkStmt->execute();
$checkStmt->store_result();
$insertStmt = $conn->prepare("INSERT INTO bank (pname, cid, arrdate, amt) VALUES (?, ?, ?, ?)");
$updateStmt = $conn->prepare("UPDATE bank SET arrdate = ?, amt = amt + ? WHERE pname = ? AND cid = ?");
if ($checkStmt->num_rows > 0) {
    // Запись уже существует, выполняем обновление
    $updateStmt->bind_param("siss", $arrdate, $amt, $pname, $cid);
    $updateStmt->execute();

    if ($updateStmt->affected_rows > 0) {
        echo "Запись обновлена";
    } else {
        echo "Ошибка при обновлении записи";
    }
} else {
    // Запись не существует, выполняем вставку
    $insertStmt->bind_param("sssi", $pname, $cid, $arrdate, $amt);
    $insertStmt->execute();

    if ($insertStmt->affected_rows > 0) {
        echo "Запись добавлена";
    } else {
        echo "Ошибка при добавлении записи (проверьте номер клиента): " . $insertStmt->error;
    }
}

echo '<br><button onclick="document.location=\'http://localhost/bank/scripts/wares.php\'">Назад</button>';

$checkStmt->close();
$updateStmt->close();
$insertStmt->close();
$conn->close();
?>

