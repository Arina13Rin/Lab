<?php
include "dbinfo.php";

if(!empty($_POST['passport'])) 
{
    $link = establish();
    health("clients");
    if (empty($_POST["FIO"]) || empty($_POST["birth"])) return;
    $passport  = $_POST["passport"];
    $FIO = $_POST["FIO"];
    $register = $_POST["regis"];
    $birth = $_POST["birth"];
    $query = "INSERT INTO clients(Passport, FIO, Register, Birth) VALUES ($passport,'$FIO','$register', '$birth');";
    $link -> query($query);
    echo ("Информация успешно занесена");
}

?>
<html>
    <head>
        <title>Добавить клиента</title>
        <link rel="stylesheet" href="index.css">
        <meta charset="utf-8">
    </head>
    <body>
        <a href="index.php">Домой</a>
        <h1>Добавление клиента</h1>
        <form action="" method="POST">
            <p>ФИО</p>
            <input type="text" name="FIO">
            <p>Серия номер паспорта</p>
            <input type="number" name="passport">
            <p>Регистрация</p>
            <input type="text" name="regis" id="">
            <p>Дата рождения</p>
            <input type="date" name="birth">
            <input type="submit" value="Добавить клиента">
        </form>
    </body>
</html>