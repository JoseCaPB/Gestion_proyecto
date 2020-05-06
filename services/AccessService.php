<?php

namespace app\services;

use Yii;
use \yii\web\ForbiddenHttpException;
use app\models\SesionesAbiertas;
use app\models\Usuarios;
use DateTime;
use DateTimeZone;

class AccessService
{
    
    // funcion que comprueba si ha caducado un tokken
    // debe recibir el access_tokken del usuario
    public static function isExpired()
    {      
        $token = Yii::$app->getRequest()->getQueryParam('access_token');
        $sesion = SesionesAbiertas::findIdentityByAccessToken($token);
        
        //$fechaActual = date('Y-m-d H:i:s', strtotime('now'));
        $fechaActual= new DateTime("now", new DateTimeZone('Europe/Madrid'));
        $fechaActual= $fechaActual->format('Y-m-d H:i:s');
        if(!$sesion){
            return true;
        }
        if($sesion->permanente == 1){
            $sesion->fechaAlta = $fechaActual;
            $sesion->update();
            return false;
        }
        $fecha = \DateTime::createFromFormat('Y-m-d H:i:s', $sesion->fechaAlta);
        date_add($fecha, date_interval_create_from_date_string('8 hours'));
        $caducidad  = $fecha->format('Y-m-d H:i:s');
    
        if($fechaActual > $caducidad) {
            $sesion->delete();
            return true;
        
        } else {
            $sesion->fechaAlta = $fechaActual;
            $sesion->update();
            return false;
        }
    }


    

}