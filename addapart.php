<?php
include "dbinfo.php";
if (!empty($_POST["street"]))
{
    health("apartments");
    $link = establish();
    $street  = $_POST["street"];
    $house = $_POST["house"];
    $floor = $_POST["floor"];
    $apart = $_POST["apart"];
    $query = "INSERT INTO apartments(Street, House, Floor, Apart) VALUES ('$street','$house',$floor,'$apart')";
    $link -> query($query);
}
?>
<html>
    <head>
        <title>Добавить апартаменты</title>
        <link rel="stylesheet" href="index.css">
        <meta charset="utf-8">
    </head>
    <body>
        <a href="index.php">Домой</a>
        <h1>Добавление апартаментов</h1>
        <form action="" method="POST">
            <p>Улица</p>
            <input type="text" name="street">
            <p>Дом</p>
            <input type="text" name="house">
            <p>Этаж</p>
            <input type="number" name="floor">
            <p>Номер помещения (квартиры)</p>
            <input type="text" name="apart">
            <input type="submit" value="Добавить апартаменты">
        </form>
    </body>
</html>