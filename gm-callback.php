<?php
    require_once __DIR__ . '/header.php';
    require_once __DIR__ . '/app/gm_setup.php';
    require_once __DIR__ . '/app/loginScript.php';
    $errors = '';
try{
    //check if google is sending bac a code
    if(isset($_GET['code'])){
        $client->fetchAccessTokenWithAuthCode($_GET['code']);
        
        if($client->getAccessToken()){
            $_SESSION['google_access_token'] = $client->getAccessToken();
            $user = $client->verifyIdToken();
            
            $exists = $db->prepare("SELECT * FROM users WHERE provider_id = :pid OR email = :email");
            $user['email'] != "" ? $email = $user['email'] : $email = "xxxx";
            $exists->execute([':pid' => $user['sub'], ':email' => $email]);
            
            if($rs = $exists->fetch()){
                $_SESSION['avatar'] = $rs['avatar'];
                $_SESSION['username'] = $rs['username'];
                $_SESSION['id'] = $rs['id'];
                
                if(isset($_SESSION['errors'])) unset($_SESSION['errors']);
                header('Location: index.php');
            }
            //register user
            else{
                $insertQuery = "INSERT INTO users (username, email, provider, provider_id, avatar)
                        VALUES(:username, :email, :provider, :provider_id, :avatar)";
                
                $statement = $db->prepare($insertQuery);
                
                $statement->execute([
                    ':username' => $user['name'], ':email' => $user['email'], ':provider' => 'Google',
                    ':provider_id' => $user['sub'], ':avatar' => $user['picture']
                ]);
                
                if($statement->rowCount() == 1) {
                    $_SESSION['avatar'] = $user['picture'];
                    $_SESSION['username'] = $user['name'];
                    $_SESSION['id'] = $user['sub'];
                    
                    if(isset($_SESSION['errors'])) unset($_SESSION['errors']);
                    header('Location: index.php');
                }
            }
        }
    }
}catch (PDOException $ex){
    $errors = "PDO Error: " . $ex->getMessage();
}catch (Exception $ex){
    $errors = "General Exception: " . $ex->getMessage();
}

if($errors != ''){
    $_SESSION['errors'] = $errors;
    header('Location: index.php');
}
