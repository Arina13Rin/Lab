<?php
include "dbinfo.php";
health("pay");

if ($_GET["contracted"] == "true") {
    $contract = $_GET["contract"];
    $query = "SELECT Sum FROM acts WHERE ID=$contract;";
    $link = establish();
    $SUM = $link->query($query)->fetch_row()[0];
    $SUM = $SUM * -1;
    $today = date("Y-m-d H:i:s");
    $query = "INSERT INTO payments(Act, Payer, Amount, Created) VALUES ($contract, 'Администратор', $SUM, '$today');";
    $link->query($query);
    header("Refresh:0; url=createpayment.php");
}

if ($_GET["uncontracted"] == "true" && $_GET["uncontractedAmount"] != NULL) {
    $link = establish();
    $contract = $_GET["contract"];
    $SUM = $_GET["uncontractedAmount"] * -1;
    $today = date("Y-m-d H:i:s");
    $query = "INSERT INTO payments(Act, Payer, Amount, Created) VALUES ($contract, 'Администратор', $SUM, '$today');";
    $link->query($query);
    header("Refresh:0; url=createpayment.php");
}

if ($_GET["savePayment"] == "true" && $_GET["amount"]!=NULL) {
    $link = establish();
    $contract = $_GET["contract"];
    $SUM = $_GET["amount"];
    $today = date("Y-m-d H:i:s");
    $payer = $_GET["payer"];
    $query = "INSERT INTO payments(Act, Payer, Amount, Created) VALUES ($contract, '$payer', $SUM, '$today');";
    $link->query($query);
    header("Refresh:0; url=createpayment.php");
}
?>
<html>

<head>
    <title>Создание платежки</title>
    <meta charset="utf8">
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <a href="index.php">На домашнюю</a>
    
    <h1>Создать платеж</h1>
    <form action="">
        <select name="contract" id="">
            <?php echo ShowContracts(); ?>
        </select>
        <button name="createpay" value="true">Записать оплату</button>
        <button name="contracted" value="true">Создать списание по договору</button>
        <input type="number" name="uncontractedAmount" id="">
        <button name="uncontracted" value="true">Создать списание не по договору</button>
    </form>

    <?php
    if ($_GET["createpay"] == "true") : ?>
        <h2>Создание оплаты</h2>
        <form action="" method="GET">
            <input type="hidden" name="contract" value="<? echo $_GET["contract"] ?>">
            <p>Договор №<? echo $_GET["contract"] ?> </p>
            <p>Сумма <input type="number" name="amount" id=""></p>
            <p>Сумму внес <input type="text" name="payer" id="" value="<?php echo GetClientName() ?>"></p>
            <button name="savePayment" value="true">Записать оплату</button>
        </form>

    <?php endif; ?>

    <h2>История списаний и начислений</h2>
    <table>
        <tr>
            <td>Номер операции</td>
            <td>Договор</td>
            <td>Создатель</td>
            <td>Сумма</td>
            <td>Сформирован</td>
        </tr>
        <?php echo ConstructHistory(); ?>
    </table>
</body>

</html>
<?php
function ShowContracts()
{
    $link = establish();
    $query = "SELECT ID, Closed FROM acts";
    $res = $link->query($query);
    $today = $date = date('Y-m-d H:i:s');
    $selectors = "";
    while ($data = $res->fetch_row()) {
        if ($data[1] != NULL) {
            $selectors .= "<option value='$data[0]'>Расторгнутый договор #$data[0]</option>";
        } else if ($data[1] == NULL) {
            $selectors .= "<option value='$data[0]'>Договор №$data[0]</option>";
        }
    }
    return $selectors;
}
function ConstructHistory()
{
    $link = establish();
    $query = "SELECT ID, Act, Payer, Amount, Created FROM payments ORDER BY ID DESC LIMIT 15";
    $result = $link->query($query);
    $constructor = "";
    if (empty($result)) return;
    while ($data = $result->fetch_row()) {
        $constructor .= "
        <tr>
            <td>$data[0]</td>
            <td><a href=\"showcontract.php?id=$data[1]\">$data[1]</td>
            <td>$data[2]</td>
            <td>$data[3]</td>
            <td>$data[4]</td>
        </tr>
        ";
    }
    return $constructor;
}
function GetClientName()
{
    $link = establish();
    $contract = $_GET["contract"];
    $query = "SELECT Client FROM acts WHERE ID = $contract";
    $id = $link->query($query)->fetch_row()[0];
    $query = "SELECT FIO FROM clients WHERE Passport=$id";
    $result = $link->query($query);
    return $result->fetch_row()[0];
}
?>