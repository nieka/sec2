<?php
require_once 'core/init.php';
$_db = db::getInstance();
if(!empty($_POST["naam"]) && !empty($_POST["bericht"]) && !empty($_POST["wachtwoord"])){
    error_reporting(0);
    $encryptedData = openssl_encrypt($_POST["bericht"],"aes128",$_POST["naam"]);
    $_db->insert("sec2", array(
        "naam" => $_POST["naam"],
        "bericht" => $encryptedData
    ));

    echo "bericht encrypted opgeslagen";
}
?>

<html>
<head>
    <title>encrypt software</title>
</head>
<body>
    <h1>Encrypt jouw geheime berichten</h1>
    <form action="" method="post">
        <p>
            <strong>Naam:</strong>
            <br>
            <input name="naam" type="text" required >
        </p>
        <p>
            <strong>Bericht:</strong>
            <br>
            <input name="bericht" required>
        </p>
        <p>
            <strong>Wachtwoord:</strong>
            <br>
            <input type="password" name="wachtwoord" required>
            <br>
            <button type="submit">Save</button>
        </p>
    </form>

    <a href="getmessage.php">Haal je bericht op</a>
</body>
</html>