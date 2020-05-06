<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $idUsuario
 * @property string $nombre
 * @property string $email
 * @property string $password
 *
 * @property ActividadesUsuarios[] $actividadesUsuarios
 * @property ProyectosUsuarios[] $proyectosUsuarios
 * @property Sesionesabiertas[] $sesionesabiertas
 */
class Usuarios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'email', 'password'], 'required'],
            [['nombre'], 'string', 'max' => 100],
            [['email'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idUsuario' => 'Id Usuario',
            'nombre' => 'Nombre',
            'email' => 'Email',
            'password' => 'Password',
        ];
    }

    /**
     * Gets query for [[ActividadesUsuarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActividadesUsuarios()
    {
        return $this->hasMany(ActividadesUsuarios::className(), ['idUsuario' => 'idUsuario']);
    }

    /**
     * Gets query for [[ProyectosUsuarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProyectosUsuarios()
    {
        return $this->hasMany(ProyectosUsuarios::className(), ['idUsuario' => 'idUsuario']);
    }

    /**
     * Gets query for [[Sesionesabiertas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSesionesabiertas()
    {
        return $this->hasMany(Sesionesabiertas::className(), ['idUsuario' => 'idUsuario']);
    }
}
