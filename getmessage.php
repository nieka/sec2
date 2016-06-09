<?php
require_once 'core/init.php';
$_db = db::getInstance();
if(!empty($_POST["naam"]) && !empty($_POST["wachtwoord"])){
    $_db->get("sec2",array("naam", "=",$_POST["naam"]));
   // var_dump($_db->results());
    $bericht = $_db->results();//["bericht"];
    $decrepted_message = openssl_decrypt($bericht[0]->bericht,"aes128",$_POST["wachtwoord"]);
    echo "Je bericht is= ";
    echo $decrepted_message;
}
?>

<html>
<head>
    <title>encrypt software</title>
</head>
<body>
<h1>Voer je naam en wachtwoord in om je bericht op te halen</h1>
<form action="" method="post">
    <p>
        <strong>Naam:</strong>
        <br>
        <input name="naam" type="text" required >
    </p>
    <p>
        <strong>Wachtwoord:</strong>
        <br>
        <input type="password" name="wachtwoord" required>
        <br>
        <button type="submit">Save</button>
    </p>
</form>

<a href="index.php"> encrypt message</a>
</body>
</html>