<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "otdel".
 *
 * @property int $otdel_id
 * @property string $name
 * @property int $active
 *
 * @property Special[] $specials
 * @property Subject[] $subjects
 * @property Teacher[] $teachers
 */
class Otdel extends ActiveRecord
{
    public function loadAndSave($bodyParams){
        $otdel = ($this->isNewRecord) ? new Otdel() : Otdel::findOne($this->otdel_id);
        if ($otdel->load($bodyParams, '') && $otdel->save()) {
            if ($this->isNewRecord) {
                $this->otdel_id = $otdel->otdel_id;
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
            'otdel_id' => function () { return $this->otdel_id;},
            'name' => function () { return $this->name;},
            'active' => function () { return $this->active;},
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'otdel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['active'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'otdel_id' => 'Otdel ID',
            'name' => 'Name',
            'active' => 'Active',
        ];
    }

    /**
     * Gets query for [[Specials]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSpecials()
    {
        return $this->hasMany(Special::className(), ['otdel_id' => 'otdel_id']);
    }

    /**
     * Gets query for [[Subjects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubjects()
    {
        return $this->hasMany(Subject::className(), ['otdel_id' => 'otdel_id']);
    }

    /**
     * Gets query for [[Teachers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeachers()
    {
        return $this->hasMany(Teacher::className(), ['otdel_id' => 'otdel_id']);
    }
}
