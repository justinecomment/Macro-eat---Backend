<?php

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

    include_once('accueil.php');
    require_once('db.php');

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $connection = new PDO("mysql:host=$HOST;dbname=$DBNAME", $USER, $PASS);

    }

?>