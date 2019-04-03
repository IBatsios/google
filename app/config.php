<?php

return [
    'database' => [
        'dsn' => 'mysql:host=localhost;dbname=bookit',
        'username' => 'root',
        'password' => '123456'
    ],
    'fb' => [
        'id' => 'facebook_app_id',
        'secret' => 'facebook_app_secret',
        'version' => 'v2.9',
        'permission' => ['email'],
        'callback_url' => 'callback_url'
    ],
    'google' => [
        'id' => '601469690265-ur4a5vfj2mkpeim0lhik9pjrvu4lruj1.apps.googleusercontent.com',
        'secret' =>'erGg1m-Msi8kxG93GwHnwNfP',
        'callback_url' => 'http://localhost/bookit',
        'scope' => ['https://www.googleapis.com/auth/userinfo.profile',
                    'https://www.googleapis.com/auth/userinfo.email',
                    'https://www.googleapis.com/auth/calendar']
    ]
];