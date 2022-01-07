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
        <title>Данные о договоре</title>
        <meta charset="utf8">
        <link rel="stylesheet" href="index.css">
    </head>
    <body>
        <a href="index.php">На домашнюю</a>
        <form action="" method="GET">
            <select name="id" multiple>
                <?php echo ShowContracts()?>
            </select><br>
            <p><input type="checkbox" name="ShowClosed" id="" <?php if ($_GET["ShowClosed"]=="on") echo 'checked'?>>Отображать расторгнутые договора</p>
            <input type="submit" value="Обновить">
        </form>
        <h1>Данные о договоре</h1>
        <?php echo Detalization()?>
    </body>
</html>
<?php
function ShowContracts()
{
    $link = establish();
    $query = "SELECT ID, Closed FROM acts";
    $res = $link -> query($query);
    $today = $date = date('Y-m-d H:i:s');
    $selectors = "";
    while ($data = $res->fetch_row())
    {
        if ($data[1]!=NULL && $_GET["ShowClosed"]=="on")
        {
            $selectors .= "<option value='$data[0]'>Расторгнутый договор #$data[0]</option>";
        }
        else if ($data[1]==NULL)
        {
            $selectors .= "<option value='$data[0]'>Договор #$data[0]</option>";
        }
    }
    return $selectors;
}

function Detalization()
{
    if (empty($_GET["id"])) return;
    $id = $_GET["id"];
    $link = establish();
    $query = "SELECT Client, Apart, Sum, Created, Closed  FROM acts WHERE ID=$id;";
    $result = $link->query($query);
    $constructor = "";
    while ($data = $result-> fetch_row())
    {
        $closed = "";
        if ($data[4]==NULL) $closed="Договор еще не расторгнут";
        else $closed = $data[4];
        $FIO = GetClient($data[0]);
        $constructor .=
        "
        <form>
        <input type=\"hidden\" name=\"id\" value='$id'>
        <h4>Номер договора</h4>
        <p>$id</p>
        <h4>Клиент</h4>
        <p>$FIO</p>
        <h4>Апартаменты</h4>
        <p>$data[1]</p>
        <h4>Стоимость в месяц</h4>
        <p>$data[2]</p>
        <h4>Договор заключен</h4>
        <p>$data[3]</p>
        <h4>Договор расторгнут</h4>
        <p>$closed</p>";
        
        if ($data[4]==NULL) $constructor.="<p><input type=\"checkbox\" name=\"terminate\">Расторнуть договор</p>";

        $constructor .= "<input type=\"submit\" value=\"Обновить\">
        </form>
        ";
    }
    return $constructor;
}

function GetClient($id)
{
    health("clients");
    $link = establish();
    $query = "SELECT FIO FROM clients WHERE Passport=$id";
    $result = $link->query($query);
    return $result -> fetch_row()[0];
}
?>