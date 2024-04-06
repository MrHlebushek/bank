<?php
// Подключение к базе данных
session_start();

if ($_SESSION["position"] != "accountant") {
    header("Location: http://localhost/bank/index.php");
    exit;
}

echo "<h1>Банковские счета</h1>";


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "course";

$conn = new mysqli($servername, $username, $password, $dbname);

mysqli_set_charset($conn, "utf8");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//просмотр таблицы
$sql = "SELECT pid, pname, cid, arrdate, amt FROM bank";
$result = $conn->query($sql);

// Проверяем, есть ли данные
if ($result->num_rows > 0) {
    // Создаем таблицу
    echo "<table border=1><tr><th>ID</th><th>Название</th><th>ID клиента</th><th>Дата последнего обновления</th><th>Количество денежных средств</th></tr>";
    // Выводим данные каждой строки
    while($row = $result->fetch_assoc()) {
        // Заполняем таблицу данными
        echo "<tr><td>" . $row["pid"]. "</td><td>" . $row["pname"]. "</td><td>" . $row["cid"]. "</td><td>" . $row["arrdate"] . "</td><td>" . $row["amt"] . "</td></tr>";
    }
    // Закрываем таблицу
    echo "</table>";
}
 else {
    echo "0 результатов";
}

$conn->close();
?>

<head>

<style>

 body {
background-image: url(background.png);
background-repeat: no-repeat;
background-position: center center;
background-attachment: fixed;
-webkit-background-size: cover;
-moz-background-size: cover;
-o-background-size: cover;
background-size: cover;
}
h1 {
color: #333;
text-align: center;
margin-top: 50px;
}
form {
display: flex;
justify-content: center;
margin-top: 30px;
}

input[type="submit"] {
background-color: #DC143C;
color: white;
padding: 10px 20px;
border: none;
border-radius: 5px;
cursor: pointer;
margin-right: 10px;
}
input[type="submit"]:hover {
background-color: #3e8e41;
}
a {
display: block;
text-align: center;
margin-top: 30px;
color: #333;
text-decoration: none;
}
a:hover {
text-decoration: underline;
}
table {
border-collapse: collapse;
margin: 20px auto;
font-size: 1.2em;
min-width: 400px;
box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
}

h1 {
    color: #333;
    text-align: center;
    margin-top: 50px;
}

form {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 30px;
    width: 50%;
    margin: 0 auto;
    background-color: #eee;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}

label {
    margin-bottom: 10px;
    font-weight: bold;
}

input[type="text"],
input[type="password"],
select {
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 3px;
    width: 100%;
    box-sizing: border-box;
    margin-bottom: 10px;
}

input[type="submit"],
button {
    background-color: #DC143C;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
}

input[type="submit"]:hover,
button:hover {
    background-color: #DC143C;
}

table th,
table td {
    padding: 8px 5px;
}

table th {
    background-color: #DC143C;
    color: white;
    text-align: left;
}

table tr:nth-of-type(even) {
    background-color: #eee;
}

table tr:hover {
    background-color: #bbb;
}

table td:last-child {
    text-align: center;
}

table input[type="text"] {
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 3px;
    width: 100%;
    box-sizing: border-box;
}

table select {
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 3px;
    width: 100%;
    box-sizing: border-box;
}

table input[type="submit"] {
    background-color: #DC143C;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

table input[type="submit"]:hover {
    background-color: #3e8e41;
}

input[type="submit"]:hover,
button:hover {
    background-color: #3e8e41;
}
</style>
</head>

<h1>Пополнение счета</h1>
<form action="addwares.php" method="post">
    <label for="pname">Клиентское название счета:</label>
    <input type="text" name="pname" id="pname" required><br>

    <label for="cid">ID клиента:</label>
    <input type="text" name="cid" id="cid" required><br>

    <label for="arrdate">Дата:</label>
    <input type="date" name="arrdate" id="arrdate" required><br>

    <label for="amt">Количество денежных средств:</label>
    <input type="number" name="amt" id="amt" required><br>

    <input type="submit" value="Добавить запись">

<br><a href="http://localhost/bank/users/accountant.php">Назад</a>
</form>