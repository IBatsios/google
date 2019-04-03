<?php
$client = new Google_Client();
$client->setApplicationName('Login to Devscreencast');
$client->setClientId($config['google']['id']);
$client->setClientSecret($config['google']['secret']);

$client->setScopes($config['google']['scope']);
$client->setRedirectUri($config['google']['callback_url']);

//instantiate Google oauth 2 service
$oauth2 = new Google_Service_Oauth2($client);

//prepare login URL
$googleLoginUrl = $client->createAuthUrl();