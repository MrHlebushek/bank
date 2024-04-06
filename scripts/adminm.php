<?php

// Подключение к базе данных
session_start();

if ($_SESSION["position"] != "admin") {

    header("Location: http://localhost/bank/index.php");
    exit;

}

echo "<h1>Пользователи</h1>";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "course";

$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8");

if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);

}

// Запрос на выборку данных из таблицы "users"

$sql = "SELECT id, login, password, position FROM users";
$result = $conn->query($sql);
// Проверяем, есть ли данные

if ($result->num_rows > 0) {

    // Выводим данные в виде HTML таблицы
    echo "<table border=1>";
    echo "<tr><th>ID</th><th>Логин</th><th>Пароль</th><th>Должность</th><th>Действие</th></tr>";

    while($row = $result->fetch_assoc()) {

        echo "<tr><form action='adminm.php' method='get'><td>" . $row["id"] . "</td><td><input type='text' name='login' value='" . $row["login"] . "' required></td><td><input type='text' name='password' value='" . $row["password"] . "' required></td><td><select name='position' required><option value='accountant' " . ($row["position"] == "accountant" ? "selected" : "") . ">Бухгалтер</option><option value='HR' " . ($row["position"] == "HR" ? "selected" : "") . ">HR</option><option value='admin' " . ($row["position"] == "admin" ? "selected" : "") . ">Администратор</option></select></td><td><input type='hidden' name='id' value='" . $row["id"] . "'><input type='submit' name='action' value='Удалить'><input type='submit' name='action' value='Обновить'></td></form></tr>";

    }

    echo "</table>";

} else {

    echo "0 результатов";

}

// Обработка действия "Удалить" "Обновить"

if ($_SERVER["REQUEST_METHOD"] == "GET" && ($_GET["action"] == "Удалить" || $_GET["action"] == "Обновить" || $_GET["action"] == "Добавить")) {

    $id = $_GET["id"];
    $login = $_GET["login"];
    $password = $_GET["password"];
    $position = $_GET["position"];

    if ($_GET["action"] == "Удалить") {

        $sql = "DELETE FROM users WHERE id='$id'";

        if (mysqli_query($conn, $sql)) {
            echo "Запись успешно удалена";
        } else {
            echo "Ошибка: " . mysqli_error($conn);
        }

} elseif ($_GET["action"] == "Обновить") {

    $id = $_GET["id"];
    $login = $_GET["login"];
    $password = $_GET["password"];
    $position = $_GET["position"];

    $sql = "SELECT * FROM users WHERE (login='$login' OR password='$password') AND id!='$id'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "Ошибка: запись с таким же логином или паролем уже существует";
    } else {
        $sql = "UPDATE users SET login='$login', password='$password', position='$position' WHERE id='$id'";
        if (mysqli_query($conn, $sql)) {
            echo "Запись успешно обновлена";
        } else {
            echo "Ошибка: " . mysqli_error($conn);
        }
    }
} elseif($_GET["action"] == "Добавить") {

        $login = $_GET["loginm"];
        $password = $_GET["passwordm"];
        $position = $_GET["positionm"];
        $sql = "SELECT * FROM users WHERE login='$login' OR password='$password'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {

            echo "Ошибка: запись с таким же логином или паролем уже существует";

        } else {
            $sql = "INSERT INTO users (login, password, position) VALUES ('$login', '$password', '$position')";
            if (mysqli_query($conn, $sql)) {

                echo "Запись успешно добавлена";

            } else {
                echo "Ошибка: " . mysqli_error($conn);
            }

        }

    }
    echo "<script>
    setTimeout(() => {
        window.location.href = 'http://localhost/bank/scripts/adminm.php';
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
<h1>Добавить пользователя</h1>
<form action="adminm.php" method="get">
    <label for="loginm">Логин:</label>
    <input type="text" name="loginm" id="loginm" required><br>
    <label for="passwordm">Пароль:</label>
    <input type="text" name="passwordm" id="passwordm" required><br>
    <label for="positionm">Должность:</label>
    <select id="positionm" name="positionm" required>
        <option value="accountant">Бухгалтер</option>
        <option value="HR">HR</option>
        <option value="admin">Администратор</option>
    </select>
    <input type="submit" name="action" value="Добавить">

    <br><button type="submit" name="back" id="back" onclick="document.location='http://localhost/bank/users/admin.php'">Назад</button>


<script>
// Получаем все текстовые поля на странице, кроме полей loginm, passwordm и positionm
const textFields = document.querySelectorAll('input[type="text"]:not(#loginm):not(#passwordm):not(#positionm):not(#back)');

// Добавляем обработчик события "click" на всю страницу
document.addEventListener('click', (event) => {
  // Проверяем, что клик был не на текстовом поле и не на кнопке
  if (!event.target.matches('input[type="text"]') && !event.target.matches('input[type="submit"]')) {
    // Сбрасываем значения всех текстовых полей на изначальные
    textFields.forEach((textField) => {
      textField.value = textField.defaultValue;
    });
  }
});

</script>

</form>