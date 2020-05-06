<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "incidencias".
 *
 * @property int $idIncidencia
 * @property string $nombre
 * @property string $descripcion
 * @property int $idActividad
 *
 * @property Actividades $idActividad0
 */
class Incidencias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'incidencias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'descripcion', 'idActividad'], 'required'],
            [['idActividad'], 'integer'],
            [['nombre', 'descripcion'], 'string', 'max' => 100],
            [['idActividad'], 'exist', 'skipOnError' => true, 'targetClass' => Actividades::className(), 'targetAttribute' => ['idActividad' => 'idActividad']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idIncidencia' => 'Id Incidencia',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'idActividad' => 'Id Actividad',
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
}
