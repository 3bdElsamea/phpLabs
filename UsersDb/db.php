<?php

function connect_pdo()
{

    $dsn = 'mysql:dbname=cafe;host=127.0.0.1;port=3306;';
    $user = 'root';
    $password = '';
    $db = new PDO($dsn, $user, $password);

    return $db;
}