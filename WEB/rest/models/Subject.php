<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "subject".
 *
 * @property int $subject_id
 * @property string $name
 * @property int $otdel_id
 * @property int $hours
 * @property int $active
 *
 * @property LessonPlan[] $lessonPlans
 * @property Otdel $otdel
 */
class Subject extends ActiveRecord
{
    public function loadAndSave($bodyParams){
        $subject = ($this->isNewRecord) ? new Subject() : Subject::findOne($this->subject_id);
        if ($subject->load($bodyParams, '') && $subject->save()) {
            if ($this->isNewRecord) {
                $this->subject_id = $subject->subject_id;
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
            'subject_id' => function () { return $this->subject_id;},
            'name' => function () { return $this->name;},
            'otdel_id' => function () { return $this->otdel_id;},
            'hours' => function () { return $this->hours;},
            'active' => function () { return $this->active;},
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subject';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'otdel_id', 'hours'], 'required'],
            [['otdel_id', 'hours', 'active'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['otdel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Otdel::className(), 'targetAttribute' => ['otdel_id' => 'otdel_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'subject_id' => 'Subject ID',
            'name' => 'Name',
            'otdel_id' => 'Otdel ID',
            'hours' => 'Hours',
            'active' => 'Active',
        ];
    }

    /**
     * Gets query for [[LessonPlans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLessonPlans()
    {
        return $this->hasMany(LessonPlan::className(), ['subject_id' => 'subject_id']);
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
