<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;


class Teacher extends ActiveRecord
{

    public function loadAndSave($bodyParams){
        $user = ($this->isNewRecord) ? new User() : User::findOne($this->user_id);
        if ($user->load($bodyParams, '') && $user->save()) {
            if ($this->isNewRecord) {
                $this->user_id = $user->user_id;
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
            'lastname' => function () { return $this->user->lastname;},
            'firstname' => function () { return $this->user->firstname;},
            'patronymic' => function () { return $this->user->patronymic;},
            'login' => function () { return $this->user->login;},
            'gender_id' => function () { return $this->user->gender_id;},
            'genderName' => function () { return $this->user->gender->name;},
            'birthday' => function () { return $this->user->birthday;},
            'roleName' => function () { return $this->user->roleName;},
            'active' => function () { return $this->user->active;},
            'otdelName' => function () { return $this->otdel->name;},
        ]);
    }

    public static function tableName()
    {
        return 'teacher';
    }


    public function rules()
    {
        return [
            [['user_id', 'otdel_id'], 'required'],
            [['user_id', 'otdel_id'], 'integer'],
            [['user_id'], 'unique'],
            [['otdel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Otdel::className(), 'targetAttribute' => ['otdel_id' => 'otdel_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'otdel_id' => 'Otdel ID',
        ];
    }


    public function getLessonPlans()
    {
        return $this->hasMany(LessonPlan::className(), ['user_id' => 'user_id']);
    }


    public function getOtdel()
    {
        return $this->hasOne(Otdel::className(), ['otdel_id' => 'otdel_id']);
    }


    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }


    public static function find()
    {
        return new \app\models\queries\TeacherQuery(get_called_class());
    }
}
