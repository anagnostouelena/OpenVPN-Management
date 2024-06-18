<?php

    include 'openVPNConnectionInterface.php';
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Credentials: true");
    header("Content-Type: application/json");

    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        $targetClient = $_GET['name'];
        $clientManipulation = new ClientManipulation();
        $clientManipulation->connectClient($targetClient);
    }

   
?>
