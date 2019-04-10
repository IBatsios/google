<?php
require_once  __DIR__ . '/database.php';
require_once __DIR__ . '/gm_setup.php';


if(isset($_POST['submitBtn'], $_POST['email'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    //do your validation here
    
    try{
           $query = "SELECT * FROM login WHERE email = :email AND password = :password";
           $statement = $db->prepare($query);
           $statement->execute([':email' => $email, ':password' => $password]);
           
           if($rs = $statement->fetch()){
               $_SESSION['avatar'] = $rs['avatar'];
               $_SESSION['username'] = $rs['username'];
               $_SESSION['id'] = $rs['id'];
               header('Location: index.php');
       }else{
           $error = "Invalid username or password";
       }
    }catch (PDOException $ex){
        die("Error " . $ex->getMessage());
    }

}

