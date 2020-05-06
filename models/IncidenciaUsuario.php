<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "incidencias_uaurios".
 *
 * @property int $idIncidenciaUsuario
 * @property int $idUsuario
 * @property int $idIncidencia
 *
 * @property Incidencias $idIncidencia0
 * @property Usuarios $idUsuario0
 */
class IncidenciaUsuario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'incidencias_usuarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idUsuario', 'idIncidencia'], 'required'],
            [['idUsuario', 'idIncidencia'], 'integer'],
            [['idIncidencia'], 'exist', 'skipOnError' => true, 'targetClass' => Incidencias::className(), 'targetAttribute' => ['idIncidencia' => 'idIncidencia']],
            [['idUsuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['idUsuario' => 'idUsuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idIncidenciaUsuario' => 'Id Incidencia Usuario',
            'idUsuario' => 'Id Usuario',
            'idIncidencia' => 'Id Incidencia',
        ];
    }

    /**
     * Gets query for [[IdIncidencia0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdIncidencia0()
    {
        return $this->hasOne(Incidencias::className(), ['idIncidencia' => 'idIncidencia']);
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
