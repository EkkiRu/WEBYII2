<?php

namespace app\models;
    use Yii;
    use yii\web\IdentityInterface;
    use yii\db\ActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property int $user_id
 * @property string $lastname
 * @property string $firstname
 * @property string|null $patronymic
 * @property string|null $login
 * @property string|null $pass
 * @property string|null $token
 * @property int|null $expired_at
 * @property int $gender_id
 * @property string|null $birthday
 * @property int $active
 *
 * @property Student $student
 * @property Teacher $teacher
 * @property Gender $gender
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    public $role;


    public function afterSave($insert,$changedAttributes){
        parent::afterSave($insert, $changedAttributes);
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        if (array_key_exists($this->role, $roles)) {
            $role = $auth->getRole($this->role);
            $auth->revokeAll($this->user_id);
            $auth->assign($role, $this->user_id);
        }
    }

    public function fields(){
        $fields = parent::fields();
        unset($fields['pass'], $fields['token'],
            $fields['expired_at']);
        return array_merge($fields, [
            'genderName' => function () { return $this->gender->name;},
            'roleName' => function () { return $this->roleName; },
        ]);
    }
    public function getRoleName(){
        $roles = Yii::$app->authManager->getRolesByUser($this->user_id);
        $roleName = array_key_first($roles);

        return $roles[$roleName]->description;
    }

    public function afterValidate(){
        if ($this->pass) {
            $this->setHashPassword($this->pass);
        }

        return true;
    }

    public function setHashPassword($password){
        $this->pass = Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()
            ->validatePassword($password, $this->pass);
    }
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    public static function findIdentity($id)
    {
        return static::findOne(['user_id' => $id, 'active' =>
            self::STATUS_ACTIVE]);
    }
    public static function findIdentityByAccessToken($token,$type=null)
    {
        return static::find()
            ->andWhere(['token' => $token])
            ->andWhere(['>', 'expired_at', time()])
            ->one();
    }
    public static  function findByUsername($username)
    {
        return static::findOne(['login' => $username, 'active' => self::STATUS_ACTIVE]);
    }

    public function  generateToken($expire)
    {
        $this->expired_at = $expire;
        $this->token = Yii::$app->security->generateRandomString();
    }

    public function tokenInfo()
    {
        return [
            'token' => $this->token,
            'expiredAt' => $this->expired_at,
            'fio' => $this->lastname.' '.$this->firstname. ' '.$this-> patronymic,
            'roles' => Yii::$app->authManager->
            getRolesByUser($this->user_id)
        ];
    }
    public function logout()
    {
        $this->token = null;
        $this->expired_at = null;
        return $this->save(false);
    }

    public function getAuthKey()
    {

    }
    public function validateAuthKey($authKey)
    {

    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lastname', 'firstname', 'gender_id', 'role'], 'required'],
            [['gender_id', 'active', 'expired_at'], 'integer'],
            ['birthday', 'date', 'format' => 'yyyy-MM-dd'],
            [['lastname', 'firstname', 'patronymic', 'login'], 'string', 'max' => 50],
            [['pass', 'token'], 'string', 'max' => 255],
            ['login', 'unique', 'message' => 'login invalid'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'lastname' => 'Lastname',
            'firstname' => 'Firstname',
            'patronymic' => 'Patronymic',
            'login' => 'Login',
            'pass' => 'Pass',
            'token' => 'Token',
            'expired_at' => 'Expired At',
            'gender_id' => 'Gender ID',
            'birthday' => 'Birthday',
            'active' => 'Active',
        ];
    }

    /**
     * Gets query for [[Student]].
     *
     * @return \yii\db\ActiveQuery|\app\models\queries\StudentQuery
     */
    public function getStudent()
    {
        return $this->hasOne(Student::className(), ['user_id' => 'user_id']);
    }

    /**
     * Gets query for [[Teacher]].
     *
     * @return \yii\db\ActiveQuery|\app\models\queries\TeacherQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teacher::className(), ['user_id' => 'user_id']);
    }

    /**
     * Gets query for [[Gender]].
     *
     * @return \yii\db\ActiveQuery|\app\models\queries\GenderQuery
     */
    public function getGender()
    {
        return $this->hasOne(Gender::className(), ['gender_id' => 'gender_id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\queries\UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\UserQuery(get_called_class());
    }
}
