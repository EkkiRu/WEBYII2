<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "gruppa".
 *
 * @property int $gruppa_id
 * @property string $name
 * @property int $special_id
 * @property string $date_begin
 * @property string|null $date_end
 *
 * @property Special $special
 * @property LessonPlan[] $lessonPlans
 * @property Student[] $students
 */
class Gruppa extends ActiveRecord
{
    public function loadAndSave($bodyParams){
        $gruppa = ($this->isNewRecord) ? new Gruppa() : Gruppa::findOne($this->gruppa_id);
        if ($gruppa->load($bodyParams, '') && $gruppa->save()) {
            if ($this->isNewRecord) {
                $this->gruppa_id = $gruppa->gruppa_id;
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
            'gruppa_id' => function () { return $this->gruppa_id;},
            'name' => function () { return $this->name;},
            'special_id' => function () { return $this->special_id;},
            'date_begin' => function () { return $this->date_begin;},
            'date_end' => function () { return $this->date_end;},
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gruppa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'special_id', 'date_begin'], 'required'],
            [['special_id'], 'integer'],
            [['date_begin', 'date_end'], 'safe'],
            [['name'], 'string', 'max' => 10],
            [['special_id'], 'exist', 'skipOnError' => true, 'targetClass' => Special::className(), 'targetAttribute' => ['special_id' => 'special_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'gruppa_id' => 'Gruppa ID',
            'name' => 'Name',
            'special_id' => 'Special ID',
            'date_begin' => 'Date Begin',
            'date_end' => 'Date End',
        ];
    }

    /**
     * Gets query for [[Special]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSpecial()
    {
        return $this->hasOne(Special::className(), ['special_id' => 'special_id']);
    }

    /**
     * Gets query for [[LessonPlans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLessonPlans()
    {
        return $this->hasMany(LessonPlan::className(), ['gruppa_id' => 'gruppa_id']);
    }

    /**
     * Gets query for [[Students]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudents()
    {
        return $this->hasMany(Student::className(), ['gruppa_id' => 'gruppa_id']);
    }
}
