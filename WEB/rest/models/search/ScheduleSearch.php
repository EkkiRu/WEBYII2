<?php


namespace app\models\search;
use app\models\Schedule;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
class ScheduleSearch extends Schedule
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
        $query->filterWhere(['user_id' => $this->lessonPlan->user_id]);
        $query->andFilterWhere(['LIKE', 'gruppa_id', $this->lessonPlan->gruppa_id]);

        return $dataProvider;
    }
}