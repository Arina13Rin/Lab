<?php 
$server = "localhost";
$user = "mysql";
$password = "";
$database = "rental";

function establish()
{
    global $server,$user,$password,$database;
    $link = mysqli_connect($server,$user,$password,$database);
    mysqli_set_charset($link, "utf8mb4");
    return $link;
}

function health($table)
{
    global $server, $user, $password,$database;
    $link = establish();
    $query = "";
    $restore = "";
    switch ($table)
    {
        case "clients":
            $query = "SELECT Passport FROM clients #HEALTH CHECKER";
            $restore = 
            "
            CREATE TABLE clients (
                Passport BIGINT NOT NULL UNIQUE,
                FIO VARCHAR(255) NOT NULL,
                Register VARCHAR(1023),
                Birth DATETIME
                )
            DEFAULT CHARSET=utf8mb4; #HEALTH RESTORER";
            break;
        case "apartments":
            $query = "SELECT ID FROM apartments #HEALTH CHECKER";
            $restore = 
            "
            CREATE TABLE apartments (
                ID INT NOT NULL AUTO_INCREMENT KEY,
                Street VARCHAR(1024),
                House VARCHAR(16),
                Floor INT,
                Apart VARCHAR(16)
                )
            DEFAULT CHARSET=utf8mb4; #HEALTH RESTORER
            ";
            break;
        case "acts":
            $query = "SELECT ID FROM acts #HEALTH CHECKER";
            $restore = 
            "
            CREATE TABLE acts(
                ID INT NOT NULL AUTO_INCREMENT KEY,
                Client BIGINT,
                Apart INT,
                Sum INT,
                Created TIMESTAMP,
                Closed TIMESTAMP
                )
            DEFAULT CHARSET=utf8mb4; #HEALTH RESTORER
            ";
            break;
	case "pay":
		$query = "SELECT ID FROM payments; #HEALTH CHECKER";
		$restore =
			"CREATE TABLE payments(
			ID INT AUTO_INCREMENT KEY,
			Act INT NOT NULL ,
			Payer VARCHAR(255) NOT NULL,
			Amount INT NOT NULL,
			Created TIMESTAMP NOT NULL
			)
			DEFAULT CHARSET=utf8mb4; #HEALTH RESTORER";
		break;
	default:
		return;
		break;
    }
    $result = $link->query($query);
    if (empty($result)) $link->query($restore);
}
