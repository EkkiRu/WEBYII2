<?php


namespace app\controllers;
use app\models\Classroom;
use yii\data\ActiveDataProvider;
use app\models\Otdel;
use Yii;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use yii\web\NotFoundHttpException;

class ClassroomController extends BaseController
{
    public function actionIndex(){
        return new ActiveDataProvider([
            'query' => Classroom::find(),
        ]);
    }
    public function actionCreate(){
        $classroom = new Classroom();
        return $this->saveModel($classroom);
    }
    public function actionUpdate($id){
        $classroom = $this->findModel($id);
        return $this->saveModel($classroom);
    }
    public function actionView($id){
        return $this->findModel($id);
    }
    public function findModel($id){
        $classroom = Classroom::findOne($id);
        if ($classroom === null) {

            throw new NotFoundHttpException("Classroom with ID $id not found");
        }
        return $classroom;
    }
    public function saveModel($classroom){
        if ($classroom->loadAndSave(Yii::$app->getRequest()->getBodyParams())) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $response->getHeaders()->set('Location',
                Url::toRoute(['view', 'id' => $classroom->getPrimaryKey()], true));
        } elseif (!$classroom->hasErrors()) {
            throw new ServerErrorHttpException(serialize($classroom->getErrors()));
        }
        return $classroom;
    }
}