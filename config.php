<?php
try
{
    $mysqlClient = new PDO('mysql:host=localhost;dbname=my_ludotheque_online;charset=utf8', 'root', 'root');
}
catch (Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}
?>