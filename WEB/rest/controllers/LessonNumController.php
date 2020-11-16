<?php


namespace app\controllers;
use yii\data\ActiveDataProvider;
use app\models\LessonNum;
use Yii;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use yii\web\NotFoundHttpException;
class LessonNumController extends BaseController
{
    public function actionIndex(){
        return new ActiveDataProvider([
            'query' => LessonNum::find(),
        ]);
    }
    public function actionView($id){
        return $this->findModel($id);
    }
    public function findModel($id){
        $lesson_num = LessonNum::findOne($id);
        if ($lesson_num === null) {

            throw new NotFoundHttpException("lesson_num with ID $id not found");
        }
        return $lesson_num;
    }

}