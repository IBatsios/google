<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/app/fb_setup.php';
require_once __DIR__ . '/app/database.php';
$errors = '';
try{
    $userAccessToken = $handler->getAccessToken();
    
    if(!$userAccessToken){
        if($handler->getError()){
            header('HTTP/1.0 401 Unauthorized');
          $errors = "Errors " .$handler->getError() . " Reason: " . $handler->getErrorReason()
                ." Desc: " . $handler->getErrorDescription();
        }else{
            header('HTTP/1.0 400 Bad Request');
            $errors = "Something when wrong";
        }
    }else if($userAccessToken){
        //get oauth client to manage user access token
        $oauth = $fb->getOAuth2Client();
        $tokenMetadata = $oauth->debugToken($userAccessToken);
    
        //validate api id and access token expiration
        $tokenMetadata->validateAppId($config['fb']['id']);
        $tokenMetadata->validateExpiration();
    
        if(!$userAccessToken->isLongLived()){
            $userAccessToken = $oauth->getLongLivedAccessToken($userAccessToken);
        }
    
        //login or sign up
        $response = $fb->get('/me?fields=id,email,name,picture.width(300).height(300)', (string) $userAccessToken);
        $user = $response->getGraphUser();
    
        $exists = $db->prepare("SELECT * FROM users WHERE provider_id = :pid OR email = :email");
        $user->getEmail() != "" ? $email = $user->getEmail() : $email = "xxxx";
        $exists->execute([':pid' => $user->getId(), ':email' => $email]);
    
        if($rs = $exists->fetch()){
            $_SESSION['avatar'] = $rs['avatar'];
            $_SESSION['username'] = $rs['username'];
            $_SESSION['id'] = $rs['id'];
        
            if(isset($_SESSION['errors'])) unset($_SESSION['errors']);
            header('Location: index.php');
        }else{
            $insertQuery = "INSERT INTO users (username, email, provider, provider_id, avatar)
                        VALUES(:username, :email, :provider, :provider_id, :avatar)";
        
            $statement = $db->prepare($insertQuery);
            $avatar = $user->getPicture();
            $statement->execute([
                ':username' => $user->getName(), ':email' => $user->getEmail(), ':provider' => 'Facebook',
                ':provider_id' => $user->getId(), ':avatar' => $avatar->getUrl()
            ]);
        
            if($statement->rowCount() == 1) {
                $_SESSION['avatar'] = $avatar->getUrl();
                $_SESSION['username'] = $user->getName();
                $_SESSION['id'] = $user->getId();
            
                if(isset($_SESSION['errors'])) unset($_SESSION['errors']);
                header('Location: index.php');
            }
        }
    }else{
        $_SESSION['errors'] = 'You did not authorize';
        header('Location: index.php');
    }
    
}catch (\Facebook\Exceptions\FacebookResponseException $ex){
    $errors = "Facebook graph returned an error: " . $ex->getMessage();
}catch (\Facebook\Exceptions\FacebookSDKException $ex){
    $errors = "Facebook SDK returned an error: " . $ex->getMessage();
}catch (PDOException $ex){
    $errors = "PDO Error: " . $ex->getMessage();
}

if($errors != ''){
    $_SESSION['errors'] = $errors;
    header('Location: index.php');
}


