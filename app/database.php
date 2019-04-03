<?php

try{
    $db = new PDO($config['database']['dsn'], $config['database']['username'],
        $config['database']['password']);
    
    //set pdo error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch (PDOException $ex){
    die("Connection failed ".$ex->getMessage());
}