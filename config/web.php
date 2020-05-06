<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'project-management',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'gii' => 'yii\gii\Module',
         ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Hy2rox1psdB2kmHnNzg3KVTxE4hdHblN',
            'parsers' => ['application/json' => 'yii\web\JsonParser'],

        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
        ],
        'user' => [
            'identityClass' => 'app\models\SesionesAbiertas', 
            'enableAutoLogin' => true,
            'enableSession' => false, 
            'loginUrl' => null,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'actividades', 'pluralize'=>false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'incidencias', 'pluralize'=>false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'proyectos', 'pluralize'=>false],
                'actividades/asignarActividad' => 'actividades/asignar-actividad',
                'actividades/listarPorUsuario/<id:\d+>' => 'actividades/listar-por-usuario',
                'incidencias/listarPorUsuario/<id:\d+>' => 'incidencias/listar-por-usuario',
                'incidencias/listarAsignadas' => 'incidencias/listar-asignadas',
                'incidencias/asignarIncidencia' => 'incidencias/asignar-incidencia',
                'proyectos/asignarProyecto' => 'proyectos/asignar-proyecto',
                'proyectos/listarParticipantes/<id:\d+>' => 'proyectos/listar-participantes',

            ],
        ],
        
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
