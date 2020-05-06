<?php


namespace app\controllers;
use app\models\Incidencias;
use app\models\SesionesAbiertas;
use app\models\ActividadUsuario;

use app\models\IncidenciaUsuario;
use Yii;
use yii\rest\ActiveController;
use app\services\AccessService;
use yii\web\HttpException;
use app\behaviors\BehaviorsConfig;


class IncidenciasController extends ActiveController {
    public $enableCsrfValidation = false;

    public $modelname = 'Incidencias';
    public $modelClass = 'app\models\Incidencias';

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = BehaviorsConfig::corsFilterConfig(['GET', 'POST', 'PUT', 'DELETE', 'HEAD', 'OPTIONS']);
        return $behaviors;
    }

    public function actions() {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['index']);
        unset($actions['view']);
        unset($actions['delete']);
        return $actions;
    } 
    //funcion privada para saber si un usuario es responsable de un proyecto
    private function esResponsableActividad($idProyecto){
        $token = Yii::$app->getRequest()->getQueryParam('access_token');
        $sesion = SesionesAbiertas::findIdentityByAccessToken($token);
        $idUsuario = $sesion->idUsuario;
        $responsable = ActividadUsuario::find()->where(['idUsuario' => $idUsuario, 'idRol' => 1])->one();
        return $responsable !== null;
    }
    private function esParticipanteActividad($idProyecto, $idUsuario){
        $participante = ActividadUsuario::find()->where(['idUsuario' => $idUsuario, 'idRol' => 2])->one();
        return $participante !== null;
    }
    //Añadir una actividad proyecto
    /*
        No viene especificado si todos los usuarios del proyecto (responsable ó participante) pueden crear actividades
        Se ha asumido que solo los responsables pueden.
    */

    public function actionCreate() {
        $params = Yii::$app->request->getBodyParams();
        $incidencia  = new Incidencias();
        $incidencia->attributes = $params;
        if(!$incidencia->validate()){
            throw new HttpException(400, $incidencia->getErrorSummary(true)[0]);
        }
        if(!$this->esResponsableActividad($incidencia->idActividad)){
            throw new HttpException(401, "No tiene permisos para esta acción");
        }
        if(!$incidencia->save()){
            throw new HttpException(400, $incidencia->getErrorSummary(true)[0]);
        }
        return $incidencia;
    }
        /* ¿Solo debe asignar incidencia el responsable de actividad?  */
        public function actionAsignarIncidencia(){
            $params = Yii::$app->request->getBodyParams();
    
            $incidenciaUsuario = new incidenciaUsuario();
            $incidenciaUsuario->attributes = $params;
            if(!$incidenciaUsuario->validate()){
                throw new HttpException(400, $incidenciaUsuario->getErrorSummary(true)[0]);
            }
            $idActividad = Incidencias::find()->select('idIncidencia')->where(['idIncidencia'=>$incidenciaUsuario->idIncidencia])->scalar();
            if(!$this->esResponsableActividad($idActividad)){
                throw new HttpException(401, "No tiene permisos para esta acción");
            }
            if(!$this->esParticipanteActividad($idActividad, $incidenciaUsuario->idUsuario)){//comprueba sea PARTICIPANTE en el proyecto
                throw new HttpException(400, "El usuario no participa en la actividad");
            }
            if(!$incidenciaUsuario->save()){
                throw new HttpException(400, $incidenciaUsuario->getErrorSummary(true)[0]);
            }
            return $incidenciaUsuario;
        }
    /* Lista las incidencias de las cuales él es el responsable (por ser responsable de actividad) */

    public function actionListarPorUsuario(){
        $idUsuario = Yii::$app->request->getQueryParam('id');

        $sql = "SELECT I.nombre, I.descripcion, I.idIncidencia, A.nombre as nombreActividad FROM incidencias I
        INNER JOIN actividades A ON A.idActividad = I.idActividad
        INNER JOIN actividades_usuarios AU ON AU.idActividad = A.idActividad
        WHERE AU.idUsuario = :idUsuario  AND AU.idRol = 1;";
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql)->bindValues([':idUsuario' => $idUsuario]);
        $incidencias = $command->queryAll();
        return $incidencias;
    }
    /* Lista incidencias asignadas */
    public function actionListarAsignadas(){
        $token = Yii::$app->getRequest()->getQueryParam('access_token');
        $sesion = SesionesAbiertas::findIdentityByAccessToken($token);
        $idUsuario = $sesion->idUsuario;
        $sql = "SELECT I.nombre, I.descripcion, I.idIncidencia, A.nombre as nombreActividad FROM incidencias I
        INNER JOIN actividades A ON A.idActividad = I.idActividad
        INNER JOIN incidencias_usuarios IU ON IU.idIncidencia = I.idIncidencia
        WHERE IU.idUsuario = :idUsuario ";
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql)->bindValues([':idUsuario' => $idUsuario]);
        $incidencias = $command->queryAll();
        return $incidencias;
    }

    /*  Esto comprueba antes de cada endpoint que el token no esté expirado */
    public function beforeAction($action)
    {
        if (AccessService::isExpired()) {
            throw new HttpException(401, "Sesión expirada, vuelva a conectarse.");
        }

        if (!parent::beforeAction($action)) {
            return false;
        }
        return true;
    }

}