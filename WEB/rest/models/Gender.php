<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "gender".
 *
 * @property int $gender_id
 * @property string $name
 *
 * @property User[] $users
 */
class Gender extends ActiveRecord
{
    public function fields(){
        $fields = parent::fields();
        return array_merge($fields, [
            'gender_id' => function () { return $this->gender_id;},
            'name' => function () { return $this->name;},
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gender';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'gender_id' => 'Gender ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['gender_id' => 'gender_id']);
    }
}
