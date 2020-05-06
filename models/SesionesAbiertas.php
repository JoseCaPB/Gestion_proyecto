<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sesionesabiertas".
 *
 * @property int $idSesionAbierta
 * @property int $permanente
 * @property string $token
 * @property int $idUsuario
 * @property string $fechaAlta
 *
 * @property Usuarios $idUsuario0
 */
class SesionesAbiertas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sesionesabiertas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['permanente', 'token', 'idUsuario', 'fechaAlta'], 'required'],
            [['permanente', 'idUsuario'], 'integer'],
            [['fechaAlta'], 'safe'],
            [['token'], 'string', 'max' => 45],
            [['idUsuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['idUsuario' => 'idUsuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idSesionAbierta' => 'Id Sesion Abierta',
            'permanente' => 'Permanente',
            'token' => 'Token',
            'idUsuario' => 'Id Usuario',
            'fechaAlta' => 'Fecha Alta',
        ];
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



   public static function findIdentityByAccessToken($token, $type=null )
   {
       return SesionesAbiertas::find()->where(['token' => $token])->one();
   }
}
