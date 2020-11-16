<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "student".
 *
 * @property int $user_id
 * @property int $gruppa_id
 * @property string $num_zach
 *
 * @property Gruppa $gruppa
 * @property User $user
 */
class Student extends ActiveRecord
{
    public function loadAndSave($bodyParams){
        $user = ($this->isNewRecord) ? new User() :
            User::findOne($this->user_id);
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

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'gruppa_id', 'num_zach'], 'required'],
            [['user_id', 'gruppa_id'], 'integer'],
            [['num_zach'], 'string', 'max' => 10],
            [['user_id'], 'unique'],
            [['gruppa_id'], 'exist', 'skipOnError' => true, 'targetClass' => Gruppa::className(), 'targetAttribute' => ['gruppa_id' => 'gruppa_id']],
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
            'gruppa_id' => 'Gruppa ID',
            'num_zach' => 'Num Zach',
        ];
    }

    /**
     * Gets query for [[Gruppa]].
     *
     * @return \yii\db\ActiveQuery|\app\models\queries\GruppaQuery
     */
    public function getGruppa()
    {
        return $this->hasOne(Gruppa::className(), ['gruppa_id' => 'gruppa_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\app\models\queries\UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\queries\StudentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\StudentQuery(get_called_class());
    }
}
