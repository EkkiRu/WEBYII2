<?php


namespace app\controllers;
use yii\data\ActiveDataProvider;
use app\models\Day;
use Yii;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use yii\web\NotFoundHttpException;
class DayController extends BaseController
{
    public function actionIndex(){
        return new ActiveDataProvider([
            'query' => Day::find(),
        ]);
    }

    public function actionView($id){
        return $this->findModel($id);
    }

    public function findModel($id){
        $day = Day::findOne($id);
        if ($day === null) {
            throw new NotFoundHttpException("Day with ID $id not found");
        }
        return $day;
    }
}