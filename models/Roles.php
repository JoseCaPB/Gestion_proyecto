<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "roles".
 *
 * @property int $idRol
 * @property string $nombre
 *
 * @property ActividadesUsuarios[] $actividadesUsuarios
 * @property ProyectosUsuarios[] $proyectosUsuarios
 */
class Roles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idRol', 'nombre'], 'required'],
            [['idRol'], 'integer'],
            [['nombre'], 'string', 'max' => 100],
            [['idRol'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idRol' => 'Id Rol',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * Gets query for [[ActividadesUsuarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActividadesUsuarios()
    {
        return $this->hasMany(ActividadesUsuarios::className(), ['idRol' => 'idRol']);
    }

    /**
     * Gets query for [[ProyectosUsuarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProyectosUsuarios()
    {
        return $this->hasMany(ProyectosUsuarios::className(), ['idRol' => 'idRol']);
    }
}
