<?php

namespace app\models\queries;
use yii\db\ActiveQuery;
/**
 * This is the ActiveQuery class for [[\app\models\Teacher]].
 *
 * @see \app\models\Teacher
 */
class TeacherQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \app\models\Teacher[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Teacher|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
