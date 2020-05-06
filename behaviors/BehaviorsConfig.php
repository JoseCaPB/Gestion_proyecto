<?php

namespace app\behaviors;

use Yii;
use yii\filters\Cors;
use yii\filters\auth\QueryParamAuth;
/**
 * Clase que configura por defecto los behaviors que se usan a lo largo de la aplicación.
 * No es un behavior como tal, solo una clase de configuración.
 */
class BehaviorsConfig
{
    /**
     * Configura el behavior de CORS filter.
     * @param array $metodos_aceptados Los métodos permitidos por el filtro de CORS
     */
    public static function corsFilterConfig($metodos_aceptados)
    {
        return [
            'class' => Cors::className(),
            'cors' => [
                //'Origin' => ['*'],
                'Access-Control-Allow-Origin' => ['*'], // Añadido
                'Access-Control-Request-Method' => $metodos_aceptados,
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => false, //SET TO TRUE IN PROD
                'Access-Control-Max-Age' => 86400,
            ],
        ];
    }

    
    public static function authenticatorConfig() 
    {
        return [
			'class' => QueryParamAuth::className(),
			'except' => ['options'],
			'tokenParam' => 'access_token',
		];
    }
    
}