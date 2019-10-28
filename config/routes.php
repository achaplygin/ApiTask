<?php
return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['user' => 'user'],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['auth' => 'auth'],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['task' => 'task'],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['comment' => 'comment'],
    ],
    'GET /' => 'auth/info',
    'POST /login' => 'auth/login',
    'GET task/<id:\d+>/history' => 'task/history',
    'GET task/<id:\d+>/comments' => 'task/comments',
    'PUT task/<id:\d+>/assign' => 'task/assign',
    'PUT task/<id:\d+>/status' => 'task/status',
    'POST task/<id:\d+>/comments' => 'task/add-comment',
    '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
    '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
];
