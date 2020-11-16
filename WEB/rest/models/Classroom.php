<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "classroom".
 *
 * @property int $classroom_id
 * @property string $name
 * @property int $active
 *
 * @property Schedule[] $schedules
 */
class Classroom extends \yii\db\ActiveRecord
{

    public function loadAndSave($bodyParams){
        $classroom = ($this->isNewRecord) ? new Classroom() : Classroom::findOne($this->classroom_id);
        if ($classroom->load($bodyParams, '') && $classroom->save()) {
            if ($this->isNewRecord) {
                $this->classroom_id = $classroom->classroom_id;
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
            'classroom_id' => function () { return $this->classroom_id;},
            'name' => function () { return $this->name;},
            'active' => function () { return $this->active;},
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'classroom';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['active'], 'integer'],
            [['name'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'classroom_id' => 'Classroom ID',
            'name' => 'Name',
            'active' => 'Active',
        ];
    }

    /**
     * Gets query for [[Schedules]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSchedules()
    {
        return $this->hasMany(Schedule::className(), ['classroom_id' => 'classroom_id']);
    }
}
