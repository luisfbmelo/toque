<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            /*'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',*/
            'dsn' => 'mysql:host=localhost;dbname=toque_sta',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            /*'dsn' => 'mysql:host=localhost;dbname=toque_sta',
            'username' => 'toque_dev',
            'password' => '$+x96NNkyVVA',
            'charset' => 'utf8',*/
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];