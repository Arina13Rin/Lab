<?php
include "dbinfo.php";
if (!empty($_GET["terminate"]) && $_GET["terminate"]="on")
{
    $today = $date = date('Y-m-d H:i:s');
    $id = $_GET["id"];
    $link = establish();
    $query = "UPDATE acts SET Closed='$today' WHERE ID=$id;";
    $link ->query($query);
}
?>
<html>
    <head>
        <title>Данные об апартаментах</title>
        <meta charset="utf8">
        <link rel="stylesheet" href="index.css">
    </head>
    <body>
        <a href="index.php">На домашнюю</a>
        <form action="" method="GET">
            <select name="id" multiple>
                <?php echo ShowAparts()?>
            </select>
            <input type="submit" value="Показать информацию">
        </form>
        <h1>Данные об апартаментах</h1>
        <?php echo Detalization()?>
    </body>
</html>
<?php
function ShowAparts()
{
    $link = establish();
    $query = "SELECT ID, Street, House, Apart  FROM apartments";
    $res = $link -> query($query);
    $selectors = "";
    while ($data = $res->fetch_row())
    {
        $selectors.="<option value='$data[0]'>ул $data[1], $data[2], кв. $data[3]</option>";
    }
    return $selectors;
}

function Detalization()
{
    if (empty($_GET["id"])) return;
    $id = $_GET["id"];
    $link = establish();
    $query = "SELECT Street, House, Floor, Apart  FROM apartments WHERE ID=$id;";
    $result = $link->query($query);
    $constructor = "";
    while ($data = $result-> fetch_row())
    {
        $constructor .=
        "
        <input type=\"hidden\" name=\"id\" value='$id'>
        <h4>Улица</h4>
        <p>$data[0]</p>
        <h4>Дом</h4>
        <p>$data[1]</p>
        <h4>Этаж</h4>
        <p>$data[2]</p>
        <h4>Квартира</h4>
        <p>$data[3]</p>
        ";
    }
    return $constructor;
}
?>