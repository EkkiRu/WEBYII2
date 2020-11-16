<?php


namespace app\controllers;
use yii\data\ActiveDataProvider;
use app\models\Special;
use Yii;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use yii\web\NotFoundHttpException;

class SpecialController extends BaseController
{
    public function actionIndex(){
        return new ActiveDataProvider([
            'query' => Special::find(),
        ]);
    }
    public function actionCreate(){
        $special = new Special();
        return $this->saveModel($special);
    }
    public function actionUpdate($id){
        $special = $this->findModel($id);
        return $this->saveModel($special);
    }
    public function actionView($id){
        return $this->findModel($id);
    }
    public function findModel($id){
        $special = Special::findOne($id);
        if ($special === null) {

            throw new NotFoundHttpException("Special with ID $id not found");
        }
        return $special;
    }
    public function saveModel($special){
        if ($special->loadAndSave(Yii::$app->getRequest()->getBodyParams())) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $response->getHeaders()->set('Location',
                Url::toRoute(['view', 'id' => $special->getPrimaryKey()], true));
        } elseif (!$special->hasErrors()) {
            throw new ServerErrorHttpException(serialize($special->getErrors()));
        }
        return $special;
    }
}