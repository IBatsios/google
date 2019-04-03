<?php

$fb = new \Facebook\Facebook([
    'app_id' => $config['fb']['id'],
    'app_secret' => $config['fb']['secret'],
    'default_graph_version' => $config['fb']['version']
]);

//get a facebook authentication access token entity
$handler = $fb->getRedirectLoginHelper();

//prepare callback login URL with permission
$callbackUrl = $handler->getLoginUrl($config['fb']['callback_url'], $config['fb']['permission']);