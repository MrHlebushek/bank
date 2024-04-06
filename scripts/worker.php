<?php
session_start();
if ($_SESSION["position"] != "HR") {
    header("Location: http://localhost/bank/index.php");
    exit;
}

echo "<h1>Сотрудники</h1>";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "course";

$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT wid, wname, speciality, shift FROM workers";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border=1>";
    echo "<tr><th>ID</th><th>Имя</th><th>Специальность</th><th>Смена</th><th>Действие</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><form action='worker.php' method='get'><td>" . $row["wid"] . "</td><td><input type='text' name='wname' value='" . $row["wname"] . "' required></td><td><select name='speciality' required><option value='охранник' " . ($row["speciality"] == "охранник" ? "selected" : "") . ">Охранник</option><option value='бухгалтер' " . ($row["speciality"] == "бухгалтер" ? "selected" : "") . ">Бухгалтер</option><option value='уборщик' " . ($row["speciality"] == "уборщик" ? "selected" : "") . ">Уборщик</option><option value='менеджер' " . ($row["speciality"] == "менеджер" ? "selected" : "") . ">Менеджер</option></select></td><td><select name='shift' required><option value='дневная' " . ($row["shift"] == "дневная" ? "selected" : "") . ">Дневная</option><option value='ночная' " . ($row["shift"] == "ночная" ? "selected" : "") . ">Ночная</option></select></td><td><input type='hidden' name='wid' value='" . $row["wid"] . "'><input type='submit' name='action' value='Удалить'><input type='submit' name='action' value='Обновить'></td></form></tr>";
    }
    echo "</table>";
} else {
    echo "0 результатов";
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["action"])) {
    $wid = $_GET["wid"];
    $wname = $_GET["wname"];
    $speciality = $_GET["speciality"];
    $shift = $_GET["shift"];
    $action = $_GET["action"];

    if ($action == "Удалить") {
        $sql = "DELETE FROM workers WHERE wid='$wid'";
        if (mysqli_query($conn, $sql)) {
            echo "Запись успешно удалена";
        } else {
            echo "Ошибка: " . mysqli_error($conn);
        }
    } elseif ($action == "Обновить") {
        $sql = "SELECT * FROM workers WHERE (wname ='$wname') AND wid !='$wid'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "Ошибка: запись с таким же именем, специальностью или сменой уже существует";
        } else {
            $sql = "UPDATE workers SET wname='$wname', speciality='$speciality', shift='$shift' WHERE wid='$wid'";
            if (mysqli_query($conn, $sql)) {
                echo "Запись успешно обновлена";
            } else {
                echo "Ошибка: " . mysqli_error($conn);
            }
        }
    } elseif($action == "Добавить") {
        $sql = "SELECT * FROM workers WHERE wname='$wname'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "Ошибка: запись с таким же именем уже существует";
        } else {
            $sql = "INSERT INTO workers (wname, speciality, shift) VALUES ('$wname', '$speciality', '$shift')";
            if (mysqli_query($conn, $sql)) {
                echo "Запись успешно добавлена";
            } else {
                echo "Ошибка: " . mysqli_error($conn);
            }
        }
    }

echo "<script>
    setTimeout(() => {
        window.location.href = 'http://localhost/bank/scripts/worker.php';
    }, 800);
</script>
";

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

<body> 
<h1>Добавить сотрудника</h1>
<form action="worker.php" method="get">
    <label for="wname">Имя:</label>
    <input type="text" name="wname" id="wname" required><br>
    <label for="speciality">Специальность:</label>
    <select name="speciality" id="speciality" required>
        <option value="охранник">Охранник</option>
        <option value="бухгалтер">Бухгалтер</option>
        <option value="уборщик">Уборщик</option>
        <option value="менеджер">Менеджер</option>
    </select><br>
    <label for="shift">Смена:</label>
    <select name="shift" id="shift" required>
        <option value="дневная">Дневная</option>
        <option value="ночная">Ночная</option>
    </select><br>
    <input type="submit" name="action" value="Добавить">
    <br>
    <button type="submit" onclick="document.location='http://localhost/bank/users/HR.php'">Назад</button>
</form>
</body>

<script>
    const textFields = document.querySelectorAll('input[type="text"]:not(#wname):not(#speciality):not(#shift)');
    document.addEventListener('click', (event) => {

        if (!event.target.matches('input[type="text"]') && !event.target.matches('input[type="submit"]')) {

            textFields.forEach((textField) => {
                textField.value = textField.defaultValue;
            });
        }
    });

</script>
