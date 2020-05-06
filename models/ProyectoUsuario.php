<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proyectos_usuarios".
 *
 * @property int $idProyectoUsuario
 * @property int $idUsuario
 * @property int $idProyecto
 * @property int $idRol
 *
 * @property Proyectos $idProyecto0
 * @property Roles $idRol0
 * @property Usuarios $idUsuario0
 */
class ProyectoUsuario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'proyectos_usuarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idUsuario', 'idProyecto', 'idRol'], 'required'],
            [['idUsuario', 'idProyecto', 'idRol'], 'integer'],
            [['idUsuario', 'idProyecto', 'idRol'], 'unique', 'targetAttribute' => ['idUsuario', 'idProyecto', 'idRol']],
            [['idProyecto'], 'exist', 'skipOnError' => true, 'targetClass' => Proyectos::className(), 'targetAttribute' => ['idProyecto' => 'idProyecto']],
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
            'idProyectoUsuario' => 'Id Proyecto Usuario',
            'idUsuario' => 'Id Usuario',
            'idProyecto' => 'Id Proyecto',
            'idRol' => 'Id Rol',
        ];
    }

    /**
     * Gets query for [[IdProyecto0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdProyecto0()
    {
        return $this->hasOne(Proyectos::className(), ['idProyecto' => 'idProyecto']);
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
