<?php


namespace app\controllers;
use yii\data\ActiveDataProvider;
use app\models\Subject;
use Yii;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use yii\web\NotFoundHttpException;

class SubjectController extends BaseController
{
    public function actionIndex(){
        return new ActiveDataProvider([
            'query' => Subject::find(),
        ]);
    }
    public function actionCreate(){
        $subject = new Subject();
        return $this->saveModel($subject);
    }
    public function actionUpdate($id){
        $subject = $this->findModel($id);
        return $this->saveModel($subject);
    }
    public function actionView($id){
        return $this->findModel($id);
    }
    public function findModel($id){
        $subject = Subject::findOne($id);
        if ($subject === null) {

            throw new NotFoundHttpException("Subject with ID $id not found");
        }
        return $subject;
    }
    public function saveModel($subject){
        if ($subject->loadAndSave(Yii::$app->getRequest()->getBodyParams())) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $response->getHeaders()->set('Location',
                Url::toRoute(['view', 'id' => $subject->getPrimaryKey()], true));
        } elseif (!$subject->hasErrors()) {
            throw new ServerErrorHttpException(serialize($subject->getErrors()));
        }
        return $subject;
    }
}