<?php
include "dbinfo.php";
?>
<html>
    <head>
        <title>Информация о пользователе</title>
        <meta charset="utf8">
        <link rel="stylesheet" href="index.css">
    </head>
    <body>
        <a href="index.php">На домашнюю</a>
        <form action="" method="GET">
            <select name="client" id="">
                <?php echo ConstructClients()?>
            </select>
            <input type="submit" value="Показать">
        </form>
        <div class="persona">
            <h2>Данные о клиенте</h2>
            <?php if (!empty($_GET["client"])) echo (ShowPersona())?>
        </div>
        <div class="contracts">
            <h2>Заключенные договора</h2>
            <?php if (!empty($_GET["client"])) echo (ShowContracts())?>
        </div>
        <div class="contracts">
            <h2>История платежей</h2>
        </div>
    </body>
</html>
<?php
function ConstructClients()
{
    health("clients");
    $link = establish();
    $query = "SELECT Passport, FIO from clients";
    $result = $link->query($query);
    if (empty($result)) return "<optgroup>Клиентов нет</optgroup>";
    $constructor = "";
    while ($data = $result->fetch_row())
    {
        $constructor.="<option value='$data[0]'>$data[1]</option>";
    }
    return $constructor;
}

function ShowPersona()
{
    health("clients");
    $link = establish();
    $client = $_GET["client"];
    $query = "SELECT FIO, Register, Birth FROM clients WHERE Passport=$client";
    $result = $link -> query($query);
    if (empty($result)) return;
    $data = $result->fetch_row();
    $constructor = "
    <h4>Ф.И.О.</h4>
    <p>$data[0]</p>
    <h4>Серия номер паспорта</h4>
    <p>$client</p>
    <h4>Регистрация</h4>
    <p>$data[1]</p>
    <h4>Дата рождения</h4>
    <p>$data[2]</p>
    ";
    return $constructor;
}

function ShowContracts()
{
    health("acts");
    $link = establish();
    $client = $_GET["client"];
    $query = "SELECT ID, Apart, Sum, Created, Closed  FROM acts WHERE Client=$client;";
    $result = $link->query($query);
    $constructor = "";
    if ($result == NULL) $constructor = "<p>Договора отсутствуют</p>"; 
    else while ($data = $result-> fetch_row())
    {
        $closed = "";
        if ($data[4]==NULL) $closed="Договор еще не расторгнут";
        else $closed = $data[4];
        $constructor .=
        "
        <h4>Номер договора</h4>
        <a href=\"showcontract.php?id=$data[0]\">$data[0]</a>
        <h4>Апартаменты</h4>
        <p>$data[1]</p>
        <h4>Стоимость в месяц</h4>
        <p>$data[2]</p>
        <h4>Договор заключен</h4>
        <p>$data[3]</p>
        <h4>Договор расторгнут</h4>
        <p>$closed</p>
        ";
    }
    return $constructor;
}
?>