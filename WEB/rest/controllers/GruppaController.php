<?php


namespace app\controllers;
use yii\data\ActiveDataProvider;
use app\models\Gruppa;
use Yii;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use yii\web\NotFoundHttpException;
class GruppaController extends BaseController
{
    public function actionIndex(){
        return new ActiveDataProvider([
            'query' => Gruppa::find(),
        ]);
    }
    public function actionCreate(){
        $gruppa = new Gruppa();
        return $this->saveModel($gruppa);
    }
    public function actionUpdate($id){
        $gruppa = $this->findModel($id);
        return $this->saveModel($gruppa);
    }
    public function actionView($id){
        return $this->findModel($id);
    }
    public function findModel($id){
        $gruppa = Gruppa::findOne($id);
        if ($gruppa === null) {

            throw new NotFoundHttpException("Gruppa with ID $id not found");
        }
        return $gruppa;
    }
    public function saveModel($gruppa){
        if ($gruppa->loadAndSave(Yii::$app->getRequest()->getBodyParams())) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $response->getHeaders()->set('Location',
                Url::toRoute(['view', 'id' => $gruppa->getPrimaryKey()], true));
        } elseif (!$gruppa->hasErrors()) {
            throw new ServerErrorHttpException(serialize($gruppa->getErrors()));
        }
        return $gruppa;
    }
}