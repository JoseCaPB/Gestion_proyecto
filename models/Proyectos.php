<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proyectos".
 *
 * @property int $idProyecto
 * @property string $nombre
 *
 * @property Actividades[] $actividades
 * @property ProyectosUsuarios[] $proyectosUsuarios
 */
class Proyectos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'proyectos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idProyecto' => 'Id Proyecto',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * Gets query for [[Actividades]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActividades()
    {
        return $this->hasMany(Actividades::className(), ['idProyecto' => 'idProyecto']);
    }

    /**
     * Gets query for [[ProyectosUsuarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProyectosUsuarios()
    {
        return $this->hasMany(ProyectosUsuarios::className(), ['idProyecto' => 'idProyecto']);
    }
}
