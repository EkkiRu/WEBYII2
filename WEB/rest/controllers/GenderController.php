<?php


namespace app\controllers;
use yii\data\ActiveDataProvider;
use app\models\Gender;
use yii\web\NotFoundHttpException;
class GenderController extends BaseController
{
    public function actionIndex(){
        return new ActiveDataProvider([
            'query' => Gender::find(),
        ]);
    }

    public function actionView($id){
        return $this->findModel($id);
    }

    public function findModel($id){
        $gender = Gender::findOne($id);
        if ($gender === null) {
            throw new NotFoundHttpException("Gender with ID $id not found");
        }
        return $gender;
    }


}