<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "special".
 *
 * @property int $special_id
 * @property string $name
 * @property int $otdel_id
 * @property int $active
 *
 * @property Gruppa[] $gruppas
 * @property Otdel $otdel
 */
class Special extends ActiveRecord
{
    public function loadAndSave($bodyParams){
        $special = ($this->isNewRecord) ? new Special() : User::findOne($this->special_id);
        if ($special->load($bodyParams, '') && $special->save()) {
            if ($this->isNewRecord) {
                $this->otdel_id = $special->special_id;
            }
            if ($this->load($bodyParams, '') && $this->save()) {
                return true;
            }
        }

        return false;
    }
    public function fields(){
        $fields = parent::fields();
        return array_merge($fields, [
            'special_id' => function () { return $this->special_id;},
            'name' => function () { return $this->name;},
            'otdel_id' => function () { return $this->otdel_id;},
            'active' => function () { return $this->active;},
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'special';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'otdel_id'], 'required'],
            [['otdel_id', 'active'], 'integer'],
            [['name'], 'string', 'max' => 250],
            [['otdel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Otdel::className(), 'targetAttribute' => ['otdel_id' => 'otdel_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'special_id' => 'Special ID',
            'name' => 'Name',
            'otdel_id' => 'Otdel ID',
            'active' => 'Active',
        ];
    }

    /**
     * Gets query for [[Gruppas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGruppas()
    {
        return $this->hasMany(Gruppa::className(), ['special_id' => 'special_id']);
    }

    /**
     * Gets query for [[Otdel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOtdel()
    {
        return $this->hasOne(Otdel::className(), ['otdel_id' => 'otdel_id']);
    }
}
