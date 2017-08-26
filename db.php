<?php
$connection = null;
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
);
try{
    $connection = new PDO('mysql://hostname=localhost;dbname=pdo.dev', 'root', 'root', $options);
} catch (PDOException $ex){
    echo $ex->getMessage();
}