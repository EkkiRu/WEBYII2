<?php


namespace app\controllers;
use yii\data\ActiveDataProvider;
use app\models\Otdel;
use Yii;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use yii\web\NotFoundHttpException;
class OtdelController extends BaseController
{
    public function actionIndex(){
        return new ActiveDataProvider([
            'query' => Otdel::find(),
        ]);
    }
    public function actionCreate(){
        $otdel = new Otdel();
        return $this->saveModel($otdel);
    }
    public function actionUpdate($id){
        $otdel = $this->findModel($id);
        return $this->saveModel($otdel);
    }
    public function actionView($id){
        return $this->findModel($id);
    }
    public function findModel($id){
        $otdel = Otdel::findOne($id);
        if ($otdel === null) {

            throw new NotFoundHttpException("Otdel with ID $id not found");
        }
        return $otdel;
    }
    public function saveModel($otdel){
        if ($otdel->loadAndSave(Yii::$app->getRequest()->getBodyParams())) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $response->getHeaders()->set('Location',
                Url::toRoute(['view', 'id' => $otdel->getPrimaryKey()], true));
        } elseif (!$otdel->hasErrors()) {
            throw new ServerErrorHttpException(serialize($otdel->getErrors()));
        }
        return $otdel;
    }

}