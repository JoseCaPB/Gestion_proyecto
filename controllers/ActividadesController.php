<?php


namespace app\controllers;
use app\models\Actividades;
use app\models\SesionesAbiertas;
use app\models\ProyectoUsuario;
use app\models\ActividadUsuario;

use Yii;
use yii\rest\ActiveController;
use app\services\AccessService;
use yii\web\HttpException;
use app\behaviors\BehaviorsConfig;


class ActividadesController extends ActiveController {
    public $enableCsrfValidation = false;

    public $modelname = 'Actividades';
    public $modelClass = 'app\models\Actividades';

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
    private function esResponsable($idProyecto){
        $token = Yii::$app->getRequest()->getQueryParam('access_token');
        $sesion = SesionesAbiertas::findIdentityByAccessToken($token);
        $idUsuario = $sesion->idUsuario;
        $responsable = ProyectoUsuario::find()->where(['idUsuario' => $idUsuario, 'idRol' => 1])->one();
        return $responsable !== null;
    }

    private function esParticipante($idProyecto, $idUsuario){
        $participante = ProyectoUsuario::find()->where(['idUsuario' => $idUsuario, 'idRol' => 2])->one();
        return $participante !== null;
    }
    //Añadir una actividad proyecto
    /*
        No viene especificado si todos los usuarios del proyecto (responsable ó participante) pueden crear actividades
        Se ha asumido que solo los responsables pueden.
    */

    public function actionCreate() {
        $params = Yii::$app->request->getBodyParams();
        $actividad  = new Actividades();
        $actividad->attributes = $params;
        if(!$actividad->validate()){
            throw new HttpException(400, $actividad->getErrorSummary(true)[0]);
        }
        if(!$this->esResponsable($actividad->idProyecto)){
            throw new HttpException(401, "No tiene permisos para esta acción");
        }
        if(!$actividad->save()){
            throw new HttpException(400, $actividad->getErrorSummary(true)[0]);
        }
        return $actividad;
    }

    /* ¿Solo debe asignar actividad el responsable del proyecto?  */
    public function actionAsignarActividad(){
        $params = Yii::$app->request->getBodyParams();

        $actividadUsuario = new ActividadUsuario();
        $actividadUsuario->attributes = $params;
        if(!$actividadUsuario->validate()){
            throw new HttpException(400, $actividadUsuario->getErrorSummary(true)[0]);
        }
        $idProyecto = Actividades::find()->select('idProyecto')->where(['idActividad'=>$actividadUsuario->idActividad])->scalar();
        if(!$this->esResponsable($idProyecto)){
            throw new HttpException(401, "No tiene permisos para esta acción");
        }
        if(!$this->esParticipante($idProyecto, $actividadUsuario->idUsuario)){//comprueba sea PARTICIPANTE en el proyecto
            throw new HttpException(400, "El usuario no participa en el proyecto");
        }
        if(!$actividadUsuario->save()){
            throw new HttpException(400, $actividadUsuario->getErrorSummary(true)[0]);
        }
        return $actividadUsuario;
    }
    /* Este endpoint lo suyo es que esté protegido a un usuario con rol administrador (no contemplado) */

    public function actionListarPorUsuario(){
        $idUsuario = Yii::$app->request->getQueryParam('id');

        $sql = "SELECT A.nombre, A.idActividad, A.idProyecto, P.nombre as nombreProyecto, R.nombre as rol
            FROM actividades A
            INNER JOIN proyectos P ON A.idProyecto = P.idProyecto
            INNER JOIN actividades_usuarios AU ON AU.idActividad = A.idActividad
            INNER JOIN roles R ON R.idRol = AU.idRol
            WHERE AU.idUsuario = :idUsuario
            ORDER BY A.nombre DESC;";
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql)->bindValues([':idUsuario' => $idUsuario]);
        $actividades = $command->queryAll();
        return $actividades;
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