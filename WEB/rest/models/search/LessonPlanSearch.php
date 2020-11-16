<?php

namespace app\models\search;
use app\models\LessonPlan;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;


class LessonPlanSearch extends LessonPlan
{
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['user_id','integer'],
            ['gruppa_id','integer'],
        ]);
    }
    public function search($params)
    {
        $query = static::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->filterWhere(['user_id' => $this->user_id]);
        $query->andFilterWhere(['LIKE', 'gruppa_id', $this->gruppa_id]);

        return $dataProvider;
    }
}