<?php
include "dbinfo.php";
if (!empty($_POST["sum"]))
{
    health("acts");
    $link = establish();
    $apartid = $_POST["apart"];
    $clientpas = $_POST["client"];
    $sum = $_POST["sum"];
    $timestamp = date("Y-m-d H:i:s");
    if (empty($apartid) || empty($clientpas) || empty($sum)) return;
    $query = "INSERT INTO acts(Client, Apart, Sum, Created) VALUES ($clientpas, $apartid, $sum,'$timestamp')";
    $link -> query($query);
}
?>
<html>
    <head>
        <meta charset="utf8">
        <title>Заключение договора</title>
        <link rel="stylesheet" href="index.css">
    </head>
    <body>
        <a href="index.php">Домой</a>
        <form action="" method="POST">
            <p>Выберите клиента</p>
            <select name="client">
                <?php echo ConstructClients();?>
            </select>
            <p>Выберите апартаменты</p>
            <select name="apart" id="">
                <?php echo ConstructAparts();?>
            </select>
            <p>Укажите месячную стоимость договора</p>
            <input type="number" name="sum">
            <input type="submit" value="Заключить договор">
        </form>
    </body>
</html>
<?php
function ConstructClients()
{
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

function ConstructAparts()
{
    $link = establish();
    $query = "SELECT ID, Street, House, Apart from apartments";
    $result = $link->query($query);
    if (empty($result)) return "<optgroup>Апартаментов нет</optgroup>";
    $constructor = "";
    while ($data = $result->fetch_row())
    {
        $constructor.="<option value='$data[0]'>$data[1], $data[2], $data[3]</option>";
    }
    return $constructor;
}
?>