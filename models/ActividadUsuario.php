<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "actividades_usuarios".
 *
 * @property int $idActividadUsuario
 * @property int $idUsuario
 * @property int $idActividad
 * @property int $idRol
 *
 * @property Actividades $idActividad0
 * @property Roles $idRol0
 * @property Usuarios $idUsuario0
 */
class ActividadUsuario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'actividades_usuarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idUsuario', 'idActividad', 'idRol'], 'required'],
            [['idUsuario', 'idActividad', 'idRol'], 'integer'],
            [['idActividad'], 'exist', 'skipOnError' => true, 'targetClass' => Actividades::className(), 'targetAttribute' => ['idActividad' => 'idActividad']],
            [['idRol'], 'exist', 'skipOnError' => true, 'targetClass' => Roles::className(), 'targetAttribute' => ['idRol' => 'idRol']],
            [['idUsuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['idUsuario' => 'idUsuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idActividadUsuario' => 'Id Actividad Usuario',
            'idUsuario' => 'Id Usuario',
            'idActividad' => 'Id Actividad',
            'idRol' => 'Id Rol',
        ];
    }

    /**
     * Gets query for [[IdActividad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdActividad0()
    {
        return $this->hasOne(Actividades::className(), ['idActividad' => 'idActividad']);
    }

    /**
     * Gets query for [[IdRol0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdRol0()
    {
        return $this->hasOne(Roles::className(), ['idRol' => 'idRol']);
    }

    /**
     * Gets query for [[IdUsuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdUsuario0()
    {
        return $this->hasOne(Usuarios::className(), ['idUsuario' => 'idUsuario']);
    }
}
