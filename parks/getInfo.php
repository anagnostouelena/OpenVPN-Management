<?php
    include 'parkRepository.php';
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');

    $parksRepo = new ParksRepository();
    $parksList = $parksRepo->getParks();
    
    echo $parksList;
?>
