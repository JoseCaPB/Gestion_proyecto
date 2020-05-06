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


class ProyectosController extends ActiveController {
    public $enableCsrfValidation = false;

    public $modelname = 'Proyectos';
    public $modelClass = 'app\models\Proyectos';

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

        /* ¿Solo debe asignar un admin (no contemplado)  */
        public function actionAsignarProyecto(){
            $params = Yii::$app->request->getBodyParams();
    
            $proyectoUsuario= new ProyectoUsuario();
            if(!$proyectoUsuario->validate()){
                throw new HttpException(400, $proyectoUsuario->getErrorSummary(true)[0]);
            }
            if(!$proyectoUsuario->save()){
                throw new HttpException(400, $proyectoUsuario->getErrorSummary(true)[0]);
            }
            return $proyectoUsuario;
        }
    /* Lista los participantes de un proyecto dado 
    Solo lista el rol participante. */
        
    public function actionListarParticipantes(){
        $idProyecto = Yii::$app->request->getQueryParam('id');

        $sql = "SELECT U.nombre, U.email FROM usuarios U
        INNER JOIN proyectos_usuarios PU ON PU.idUsuario = U.idUsuario
        WHERE PU.idProyecto = :idProyecto  AND PU.idRol = 2;";
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql)->bindValues([':idProyecto' => $idProyecto]);
        $participantes = $command->queryAll();
        return $participantes;
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